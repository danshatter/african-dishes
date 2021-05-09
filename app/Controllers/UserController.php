<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;
use App\Validation\Validator;
use Slim\Views\Twig as View;
use Slim\Flash\Messages as Flash;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use App\Models\User;
use Slim\Exception\HttpNotFoundException;
use App\Exceptions\HttpLockedException;
use App\Mail\Mailer;
use Throwable;

class UserController
{
    
    private $view;
    private $validator;
    private $flash;
    private $pathToImages = __DIR__.'/../../public_html/images/profile/';
    private $allowedImages = ['image/jpeg', 'image/jpg', 'image/png'];
    private $maxImageSize = 3145728;

    public function __construct(View $view, Validator $validator, Flash $flash, RouteParser $routeParser)
    {
        $this->view = $view;
        $this->validator = $validator;
        $this->flash = $flash;
        $this->routeParser = $routeParser;
    }

    public function store(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        $data = $request->getParsedBody();

        $validData = $this->validator->ready($data)->validate($data, [
            'first_name' => V::notEmpty()->noWhitespace()->alpha(),
            'last_name' => V::notEmpty()->noWhitespace()->alpha(),
            'username' => V::notEmpty()->noWhitespace()->alnum()->usernameAvailable(),
            'email' => V::notEmpty()->email()->emailAvailable(),
            'password' => V::notEmpty()->length(5),
            'confirm_password' => V::notEmpty()->equals($data['password'])
        ]);

        if ($this->validator->failed()) {
            $_SESSION['old'] = $data;

            $_SESSION['errors'] = $this->validator->getErrors();

            return $response->withHeader('Location', $this->routeParser->urlFor('sign-up'));
        }

        $user = User::create($validData);
        
        try {
            // Send Sign Up Email
            Mailer::signUpMail($user);
        } catch (Throwable $e) {
            $error = $e;
        }
        
        $this->flash->addMessage('register-success', 'Registration successful. You can now login below');
        
        return $response->withHeader('Location', $this->routeParser->urlFor('sign-in'));
    }

    public function show(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        $data = $request->getParsedBody();
        
        $validData = $this->validator->ready($data)->validate($data, [
            'email_or_username' => V::notEmpty(),
            'password' =>V::notEmpty()
        ]);

        if ($this->validator->failed()) {
            $_SESSION['errors'] = $this->validator->getErrors();
            $_SESSION['old'] = $data;
            
            return $response->withHeader('Location', $this->routeParser->urlFor('sign-in'));
        }

        // Query to get The requested user
        $user = User::where('username', $validData['email_or_username'])
            ->orWhere('email', $validData['email_or_username'])
            ->first();
        
        //Check to verify if the user exists
        if ($user === null) {
            $_SESSION['errors'] = [
                'email_or_username' => ['This user is not registered with us']
            ];
            $_SESSION['old'] = $data;
            
            return $response->withHeader('Location', $this->routeParser->urlFor('sign-in'));
        }
        
        //Validating password
        $this->validator->validate($validData, ['password' => V::validUserPassword($user->password)]);

        if ($this->validator->failed()) {
            $this->flash->addMessage('login-failed', $this->validator->getErrors()['password']['validUserPassword']);
            
            $_SESSION['old'] = $data;
            
            return $response->withHeader('Location', $this->routeParser->urlFor('sign-in'));
        }

        // Check to make sure the user is an active user
        if ($user->role === 1) {
            if ($user->status['status'] !== 'active') {
                throw new HttpLockedException($request);

                return $response;
            }
        }

        $_SESSION['id'] = $user->id;

        return $response;
    }

    public function updateProfile(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (!isset($id)) {
            return $response;
        }

        // Check to make sure the logged in user can only update their profile
        if ((int)$request->getAttribute('user') === $id) {
            // Variables Initialization
            $user = User::find($id);
            
            if ($user === null) {
                throw new HttpNotFoundException($request);
                return $response;
            }
            
            $error = false;
            $data = $request->getParsedBody();
            $image = $request->getUploadedFiles()['image'];

            // Form input Validation
            $validData = $this->validator->ready($data)->validate($data, [
                'first_name' => V::notEmpty()->noWhitespace()->alpha(),
                'last_name' => V::notEmpty()->noWhitespace()->alpha(),
                'email' => V::notEmpty()->email()->updateEmail($user->email),
                'phone' => V::optional(V::phone()),
                'address_1' => V::optional(V::alwaysValid()),
                'address_2' => V::optional(V::alwaysValid())
            ]);

            // Image validation (any image uploaded to update current image)
            if ($image->getError() !== 4) {
                // Image validation (any error)
                if ($image->getError() !== 0) {
                    $error = 'Error occurred while uploading image. Please try again';
                }

                // Image validation for max-size upload
                if ($image->getSize() > $this->maxImageSize) {
                    $error = 'Image size must not be greater than 3MB';
                }
            }

            // Error checking
            if ($this->validator->failed() || $error) {
                $_SESSION['old'] = $data;
                $_SESSION['errors'] = $this->validator->getErrors();
                $_SESSION['errors']['image'] = $error;

                return $response->withHeader('Location', $this->routeParser->urlFor('profile'));
            }

            // Validation for present and valid image
            if ($image->getError() === 0) {
                // Validation to check if image is among allowed images
                if (!in_array($image->getClientMediaType(), $this->allowedImages)) {
                    $error = 'Uploaded images format should be .jpg, .jpeg or .png';
                }
            }

            // Error checking
            if ($this->validator->failed() || $error) {
                $_SESSION['old'] = $data;
                $_SESSION['errors'] = $this->validator->getErrors();
                $_SESSION['errors']['image'] = $error;

                return $response->withHeader('Location', $this->routeParser->urlFor('profile'));
            }

            // Making sure that an image was uploaded and no error occurred
            if ($image->getError() === 0) {
                // File extension
                $ext = pathinfo($image->getClientFilename(), PATHINFO_EXTENSION);
                $ext = strtolower($ext);

                // Set User image name
                $imageName = $user->id.'.'.$ext;

                // Joining image to query fields
                $validData = array_merge($validData, ['image' => $imageName]);
            }

            // Query database
            $user->update($validData);

            // Making sure that an image was uploaded and no error occurred again
            if ($image->getError() === 0) {
                // File
                $file = $this->pathToImages.$imageName;
                
                // Uploading image
                if (file_exists($file)) {
                    unlink($file);   
                }

                $image->moveTo($file);
            }

            // Flash message
            $this->flash->addMessage('user-update-success', 'user details updated successfully');

            return $response->withHeader('Location', $this->routeParser->urlFor('profile'));
        }

        return $response;
    }

    public function updatePassword(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (!isset($id)) {
            return $response;
        }

        // Check to make sure the logged in user can only update their profile
        if ((int)$request->getAttribute('user') === $id) {
            $user = User::find($id);
            
            if ($user === null) {
                throw new HttpNotFoundException($request);

                return $response;
            }

            $data = $request->getParsedBody();

            $validData = $this->validator->ready($data)->validate($data, [
                'old_password' => V::notEmpty()->validOldPassword($user->password),
                'password' => V::notEmpty()->length(5),
                'confirm_password' => V::notEmpty()->equals($data['password'])
            ]);

            if ($this->validator->failed()) {
                $_SESSION['errors'] = $this->validator->getErrors();

                return $response->withHeader('Location', $this->routeParser->urlFor('profile'));
            }

            $user->update($validData);

            $this->flash->addMessage('password-update-success', 'password changed successfully');

            return $response->withHeader('Location', $this->routeParser->urlFor('profile'));
        }

        return $response;
    }

    public function destroy(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (!isset($id)) {
            return $response;
        }

        session_unset();

        session_destroy();

        return $response;
    }

    public function activate(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $user = User::find($request->getAttribute('customer'));

        if (!isset($user)) {
            throw new HttpNotFoundException($request);

            return $response;
        }
        
        if ($user->status['status'] !== 'active') {
            // Set status to active
            $user->status = 1;
            
            // Saving changes
            $user->save();

            $this->flash->addMessage('activate-success', 'customer "'.$user->full_name.'" activated');
        
            return $response->withHeader('Location', $this->routeParser->urlFor('all-users'));
        }

        return $response;
    }

    public function deactivate(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $user = User::find($request->getAttribute('customer'));

        if (!isset($user)) {
            throw new HttpNotFoundException($request);

            return $response;
        }
        
        if ($user->status['status'] !== 'inactive') {
            // Set status to active
            $user->status = 0;
            
            // Saving changes
            $user->save();

            $this->flash->addMessage('deactivate-success', 'customer "'.$user->full_name.'" deactivated');
        
            return $response->withHeader('Location', $this->routeParser->urlFor('all-users'));
        }

        return $response;
    }

    public function block(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $user = User::find($request->getAttribute('customer'));

        if (!isset($user)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }
        
        if ($user->status['status'] !== 'blocked') {
            // Set status to active
            $user->status = 2;
            
            // Saving changes
            $user->save();

            $this->flash->addMessage('block-success', 'customer "'.$user->full_name.'" blocked');
        
            return $response->withHeader('Location', $this->routeParser->urlFor('all-users'));
        }

        return $response;
    }

    public function destroyUser(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (!isset($id)) {
            return $response;
        }

        // Check to make sure the logged in user can only update their profile
        if ((int)$request->getAttribute('user') === $id) {

            User::destroy($id);

            session_unset();

            $this->flash->addMessage('user-account-delete-success', 'your account has been deleted successfully');

            return $response;
        }

        return $response;
    }

    public function forgottenPassword(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        $data = $request->getParsedBody();

        $validData = $this->validator->ready($data)->validate($data, [
            'email' => V::notEmpty()->email()->validEmail(),
        ]);

        if ($this->validator->failed()) {
            $_SESSION['old'] = $data;
            
            $_SESSION['errors'] = $this->validator->getErrors();

            return $response->withHeader('Location', $this->routeParser->urlFor('forgotten-password'));
        }

        $user = User::where('email', $data['email'])->first();

        // Check to see if the user is already at a recovery state
        if ($user->recovery === 1) {
            $_SESSION['old'] = $data;
            
            $this->flash->addMessage('already-at-recovery', 'you are already in a state of recovery. Please check your email and activate your account or click the resend email button below to resend the activation email');

            return $response->withHeader('Location', $this->routeParser->urlFor('forgotten-password'));
        }

        // Just making sure the user is not in recovery
        if ($user->recovery === 0) {
            /**
             *  SEND RECOVERY EMAIL HERE
             */
            
            try {
                $token = md5(uniqid($user->id));
                
                Mailer::resetPasswordMail($user, $token);
                
                 // Set recovery state
                $user->recovery = 1;
    
                // Generate user token
                $user->token = $token;
    
                $user->save();
                
                $this->flash->addMessage('recovery-initiated', 'please check your email to reset your password');

                return $response->withHeader('Location', $this->routeParser->urlFor('forgotten-password'));
            } catch (Throwable $e) {
                $this->flash->addMessage('recovery-initiation-failed', 'an error occurred while sending the email. please try again later');

                return $response->withHeader('Location', $this->routeParser->urlFor('forgotten-password'));
            }
            
            // This will never run
            return $response;
        }

        return $response;
    }

    public function recovery(Request $request, Response $response)
    {
        $userId = $request->getAttribute('session_id');

        if (isset($userId)) {
            return $response;
        }

        $id = $request->getAttribute('id');
        $token = $request->getAttribute('token');

        $user = User::find($id);

        // Check to see if the user really exists
        if (!isset($user)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }

        // Check to see if the user is NOT in a state of recovery
        if ($user->recovery === 0) {
            throw new HttpNotFoundException($request);
            
            return $response; 
        }

        // Making sure the user is really in a state of recovery
        if ($user->recovery === 1) {
            // Check to see if the token matches
            if ($token !== $user->token) {
                throw new HttpNotFoundException($request);
            
                return $response;
            }

            // Set recovery session
            $_SESSION['recovery'] = true;

            $_SESSION['user_data'] = $id;

            // Redirect to reset password page
            return $response->withHeader('Location', $this->routeParser->urlFor('reset-password'));
        }

        return $response;
    }

    public function resetPassword(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        $recovery = $request->getAttribute('recovery');
        $userData = $request->getAttribute('user_data');

        // Check for recovery variable
        if (!isset($recovery)) {
            return $response;
        }

        // Making sure the recovery variable is true
        if (!isset($userData)) {
            return $response;
        }

        // User Profile
        $user = User::find($userData);

        // If all goes right, this will never run
        if (!isset($user)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }

        // Making sure the values have the recommended values
        if ($recovery === true && isset($userData)) {
            $data = $request->getParsedBody();

            $validData = $this->validator->ready($data)->validate($data, [
                'password' => V::notEmpty()->length(5),
                'confirm_password' => V::notEmpty()->equals($data['password'])
            ]);

            if ($this->validator->failed()) {
                $_SESSION['errors'] = $this->validator->getErrors();

                return $response->withHeader('Location', $this->routeParser->urlFor('reset-password'));
            }

            // Remove user from recovery state
            $user->recovery = 0;

            // Remove token
            $user->token = null;

            // Set user password
            $user->password = $validData['password'];

            $user->save();

            // Free session variables (user_data and recovery)
            session_unset();

            $this->flash->addMessage('reset-password-success', 'your password has been reset successfully');

            return $response->withHeader('Location', $this->routeParser->urlFor('sign-in'));
        }

        return $response;
    }

    public function resendEmail(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        $data = $request->getParsedBody();

        $validData = $this->validator->ready($data)->validate($data, [
            'email' => V::notEmpty()->email()->validEmail(),
        ]);

        if ($this->validator->failed()) {
            $_SESSION['old'] = $data;
            
            $_SESSION['errors'] = $this->validator->getErrors();

            return $response->withHeader('Location', $this->routeParser->urlFor('resend-email'));
        }

        $user = User::where('email', $validData['email'])->first();

        // This will not run if you initiated password recovery
        if ($user->recovery === 0) {
            $this->flash->addMessage('not-in-recovery', 'you have not initiated password recovery');

            return $response->withHeader('Location', $this->routeParser->urlFor('resend-email'));
        }

        if ($user->recovery === 1) {
            /**
             * Resend Recovery Email
             */
            
            try {
                Mailer::resetPasswordMail($user, $user->token);
                
                $this->flash->addMessage('resend-email-success', 'Your email was sent successfully');
            
                return $response->withHeader('Location', $this->routeParser->urlFor('resend-email'));
            } catch (Throwable $e) {
                $this->flash->addMessage('resend-email-error', 'an error occurred while sending the email. please try again later');

                return $response->withHeader('Location', $this->routeParser->urlFor('resend-email'));
            }
            
            // This will never run
            return $response;
        }

        return $response;
    }

}