(function(window, document, $, undefined) {
    "use strict";
    $(function() {


    if ($('#lineChart').length) {
        var ctx = document.getElementById('AreaChart').getContext('2d');

        var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
            gradientStroke1.addColorStop(0, '#4facfe');
            gradientStroke1.addColorStop(1, '#00f2fe');

        var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue',
                        data: [10, 8, 6, 5, 12, 8, 16, 17, 6, 7, 6, 10, 0],
                        backgroundColor: 'rgba(94, 114, 228, 0.3)',
                        borderColor: '#5e72e4',
                        borderWidth: 3
                    }]
                }
            });
        }

        if ($('#lineChart').length) {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
                    datasets: [{
                        label: 'Google',
                        data: [0, 30, 60, 25, 60, 25, 50, 0],
                        backgroundColor: "transparent",
                        borderColor: "#2dce89",
                        borderWidth: 3

                    }, {
                        label: 'Facebook',
                        data: [0, 60, 25, 80, 35, 75, 30, 0],
                        backgroundColor: "transparent",
                        borderColor: "#ff2fa0",
                        borderWidth: 3

                    }]
                }
            });
        }


        if ($('#barChart').length) {
            var ctx = document.getElementById("barChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Samsung',
                        data: [12, 19, 3, 5, 10, 5, 13, 17, 11, 8, 11, 9],
                        backgroundColor: "#ff2fa0"
                    }, {
                        label: 'Nokia',
                        data: [10, 11, 7, 5, 9, 13, 10, 16, 7, 8, 12, 5],
                        backgroundColor: "#5e72e4"
                    }]
                }, options: {
                scales: {
                    xAxes: [{
                        barPercentage: .7
                        }]
                }
                }
            });
        }

        if ($('#HorizontalbarChart').length) {
            var ctx = document.getElementById("HorizontalbarChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
                    datasets: [{
                        label: 'Google',
                        data: [13, 20, 4, 18, 29, 25, 8],
                        backgroundColor: "#172b4d"
                    }, {
                        label: 'Facebook',
                        data: [31, 30, 6, 6, 21, 4, 11],
                        backgroundColor: "#fb6340"
                    }]
                }
            });
        }


        if ($('#StackedbarChart').length) {

            var ctx = document.getElementById("StackedbarChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Google',
                        data: [65, 59, 80, 81,65, 59, 80, 81,59, 80, 81,65],
                        backgroundColor: "#ff2fa0"
                    }, {
                        label: 'Facebook',
                        data: [28, 48, 40, 19,28, 48, 40, 19,40, 19,28, 48],
                        backgroundColor: "#2dce89"
                    }, {
                        label: 'Twitter',
                        data: [35, 50, 75, 50, 60, 35, 25, 40, 55, 45, 35, 50],
                        backgroundColor: "#5e72e4"
                    }]
                }, options: {
                scales: {
                    xAxes: [{
                        stacked: true,
                        barPercentage: .3
                        }],
                    yAxes: [{
                        stacked: true
                        }]
                }
                }
            });
        }

        if ($('#radarChart').length) {
            var ctx = document.getElementById("radarChart");
            var myChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
                    datasets: [{
                        label: 'Twitter',
                        backgroundColor: "rgba(17, 205, 239, 0.3)",
                        borderColor: "#11cdef",
                        data: [13, 20, 4, 18, 29, 25, 8]
                    }, {
                        label: 'Linkedin',
                        backgroundColor: "rgba(94, 114, 228, 0.3)",
                        borderColor: "#5e72e4",
                        data: [31, 30, 6, 6, 21, 4, 11]
                    }]
                },
                options: {
                    legend: {
                    position: 'bottom',
                    display: true,
                    labels: {
                        boxWidth:40
                    }
                    }
                }
            });
        }


        if ($('#polarChart').length) {
            var ctx = document.getElementById("polarChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: ["Primary", "Success", "Danger", "Info"],
                    datasets: [{
                        backgroundColor: [
                            "#5e72e4",
                            "#2dce89",
                            "#f5365c",
                            "#11cdef"
                        ],
                        data: [14, 11, 16, 7]
                    }]
                },
                options: {
                    legend: {
                    position: 'bottom',
                    display: true,
                    labels: {
                        boxWidth:40
                    }
                    }
                }
            });
        }


        if ($('#pieChart').length) {
            var ctx = document.getElementById("pieChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ["Primary", "Success", "Info", "Secondary", "Danger", "Dark"],
                    datasets: [{
                        backgroundColor: [
                            "#5e72e4",
                            "#2dce89",
                            "#11cdef",
                            "#ff2fa0",
                            "#f5365c",
                            "#172b4d"
                        ],
                        data: [12, 19, 3, 5, 2, 3]
                    }]
                },
                options: {
                    legend: {
                    position: 'bottom',
                    display: true,
                    labels: {
                        boxWidth:40
                    }
                    }
                }
            });
        }


        if ($('#doughnutChart').length) {
            var ctx = document.getElementById("doughnutChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Primary", "Success", "Info", "Secondary", "Danger", "Dark"],
                    datasets: [{
                        backgroundColor: [
                            "#5e72e4",
                            "#2dce89",
                            "#11cdef",
                            "#ff2fa0",
                            "#f5365c",
                            "#172b4d"
                        ],
                        data: [12, 19, 3, 5, 2, 3]
                    }]
                },
                options: {
                    legend: {
                    position: 'bottom',
                    display: true,
                    labels: {
                        boxWidth:40
                    }
                    }
                }
            });
        }


    });
    
    if ($('[data-toggle="popover"]')) {
        $('[data-toggle="popover"]').popover()
    }

})(window, document, window.jQuery);


// Danshatter Javascript

(function() {
    "use strict";

    // The UI controller. It handles everything concerning the display 
    const UI = (function(){

        const UIVariables = {
            menuList: document.getElementById('menu-list'),
            categoryDropdown: document.getElementById('category-dropdown'),
            cartList: document.getElementById('cart-list'),
            modalHouse: document.getElementById('modal-house'),
            checkoutTotal: document.getElementById('checkout-total'),
            checkoutContainer: document.getElementById('checkout-container'),
            checkoutForm: document.getElementById('checkout-form'),
            checkoutErrorMessage: document.getElementById('checkout-error-message'),
            submitForms: document.getElementsByClassName('submit-form'),
            loneForms: document.getElementsByClassName('lone-form'),
            navItems: document.getElementsByClassName('nav-item'),
            logoutButton: document.querySelector('.logout-button'),
            cartCount: document.querySelector('.cart-count')
        }

        const modalState = {};

        function capitalizeFirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function capitalizeWords(string) {
            var splitStr = string.toLowerCase().split(' ');
            for (var i = 0; i < splitStr.length; i++) {
                // You do not need to check if i is larger than splitStr length, as your for does that for you
                // Assign it back to the array
                splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);     
            }
            // Directly return the joined string
            return splitStr.join(' '); 
        }

        return {

            getUIVariables() {
                return UIVariables;
            },

            setOutOfStock(item) {

            },

            getModalState() {
                return modalState;
            },

            insertModals(menuItems, cart) {
                // Modal initialization
                let modal = '';

                // insert modal string for each menu item
                menuItems.forEach(item => modal += this.cartModal(item));

                UIVariables.modalHouse.innerHTML = modal;

                // change the state of the modal to edit with the cart values in place
                cart.forEach(item => this.setModalState(item, 'edit'));
            },

            loadCart(data, quantity, cart) {
                // table of items in cart
                const menuItems = data.data;

                // All associated totals
                const totals = data.total;

                // DOM cart table initialization
                let cartTable = '';

                cartTable += `
                    <div class="table-responsive table-hover table-striped">
                        <table class="table" id="cart-items-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="border-0">
                                        <div class="p-2 px-3 text-uppercase">Product</div>
                                    </th>
                                    <th scope="col" class="border-0">
                                        <div class="py-2 text-uppercase">Quantity</div>
                                    </th>
                                    <th scope="col" class="border-0">
                                        <div class="py-2 text-uppercase">Price</div>
                                    </th>
                                    <th scope="col" class="border-0">
                                        <div class="py-2 text-uppercase">Remove</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                menuItems.forEach(item => {
                    cartTable += `
                        <tr class="cart-item" data-id="${item.id}">
                            <th scope="row" class="border-0">
                                <div class="p-2">
                                    <a href="#" data-toggle="modal" data-target="#ModalCenter${item.id}" class="image-modal">
                                        <img src="/images/menus/${item.id}.${item.image_extension}" alt="${item.name}" class="img-fluid rounded shadow-sm object-fit">
                                    </a>
                                    <div class="cart-title-product d-inline-block align-middle">
                                        <h5 class="mb-0"> <a href="#" data-toggle="modal" data-target="#ModalCenter${item.id}" class="text-dark d-inline-block align-middle">${capitalizeWords(item.name)}</a></h5><span class="text-muted font-weight-normal font-italic d-block">Category: ${capitalizeWords(item.category.name)}</span>
                                    </div>
                                </div>
                            </th>
                            <td class="border-0 align-middle"><strong>${quantity[`id${item.id}`]}</strong></td>
                            <td class="border-0 align-middle"><strong>&#8358; ${totals.item_total[`id${item.id}`]}</strong></td>
                            <td class="border-0 align-middle">
                                <form class="cart-trash-form">
                                    <button class="cart-trash"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    `
                });

                cartTable += `
                            </tbody>
                        </table>
                    </div>
                `;

                cartTable += `
                    <div class="cart-calculate">
                        <div class="row">
                            <div class="col-lg-6 ml-auto">
                                <ul class="list-unstyled mb-4">
                                    <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Total</strong>
                                        <h5 class="font-weight-bold">&#8358; ${totals.grand_total}</h5>
                                    </li>
                                </ul><a href="/checkout" class="btn btn-main rounded-pill py-2 btn-block">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                `;

                // insert everything into the DOM
                UIVariables.cartList.innerHTML = cartTable;

                // check to see if the modals are already in the page
                if (!UIVariables.modalHouse.firstElementChild) {
                    this.insertModals(menuItems, cart);
                }
            },

            setModalState(item, state) {
                // select modal in concern
                const modal = document.getElementById(`ModalCenter${item.id}`);

                // check to see if modal really exists
                if (modal !== null) {
                    // quantity input
                    const input = document.getElementById(`quantity-input-${item.id}`);

                    // cart button
                    const button = document.getElementById(`cart-btn-${item.id}`);

                    const quantityLeft = modal.dataset.quantityleft;

                    switch (state) {
                        /**
                            Name of state is gotten from the state in which the quantity input
                            and the cart button will bear
                         */
                        case 'add':
                            button.textContent = 'ADD TO CART';

                            if (input !== null) {
                                input.disabled = false;
                            }

                            modalState[`id${item.id}`] = 'add';

                            break;
                        
                        case 'remove':
                            button.textContent = 'REMOVE FROM CART';

                            if (input !== null) {
                                input.value = item.quantity;

                                input.disabled = true;
                            }

                            modalState[`id${item.id}`] = 'remove';

                            break;
                        
                        case 'edit':
                            button.textContent = 'EDIT CART ITEM';

                            if (input !== null) {
                                input.value = item.quantity;

                                input.disabled = true;
                            }

                            modalState[`id${item.id}`] = 'edit';

                            break;
                        
                        case 'update':
                            button.textContent = 'UPDATE CART ITEM';

                            if (input !== null) {
                                input.disabled = false;
                            }

                            modalState[`id${item.id}`] = 'update';

                            break;

                        default:
                            throw new Error('Invalid state selected');
                    }

                    if (parseInt(quantityLeft) <= 0 && quantityLeft !== null) {
                        button.textContent = 'OUT OF STOCK';
                    }
                }
            },
            
            addToCart(item) {
                this.addBadge(item.id);

                this.setModalState(item, 'remove');
            },

            addBadge(id) {
                const cardContainer = document.getElementById(`card-container-${id}`);
    
                // Check to know if the cart item is in the current page
                if (cardContainer !== null) {

                    const quantityLeft = cardContainer.dataset.quantityleft;

                    if (parseInt(quantityLeft) > 0) {
                        // span that displays that the food has been added to cart
                        const span = document.createElement('span');
                        span.classList.add('badge', 'badge-danger', 'food-added');
                        span.appendChild(document.createTextNode('Food Added to Cart'));
        
                        // Add add to cart span
                        const imageBox = cardContainer.querySelector('.product-image-box');
                        imageBox.insertBefore(span, imageBox.firstElementChild);
                        
                        // change plus symbol to minus symbol
                        const icon = cardContainer.querySelector('.fa-plus-circle');

                        if (icon !== null) {
                            icon.className = 'fa fa-minus-circle';
                            
                            icon.nextSibling.textContent = ' Remove';
                        }
                    }
                }
            },

            removeFromCart(item) {
                this.removeBadge(item.id);

                this.setModalState(item, 'add');
            },

            removeBadge(id) {
                const cardContainer = document.getElementById(`card-container-${id}`);

                // Check to know if the cart item is in the current page
                if (cardContainer !== null) {
                    const badge = cardContainer.querySelector('.badge');

                    if (badge !== null) {
                        // remove food added to cart badge
                        const imageBox = cardContainer.querySelector('.product-image-box');
                        imageBox.removeChild(badge);

                        // change minus symbol to plus symbol
                        const icon = cardContainer.querySelector('.fa-minus-circle'); 

                        if (icon !== null) {
                            icon.className = 'fa fa-plus-circle';
                            
                            icon.nextSibling.textContent = ' Add';
                        }
                    }
                }
            },

            insertLoader(el) {
                const loader = document.createElement('img');

                loader.setAttribute('src', '/images/loader.gif');

                loader.classList.add('pos-center');

                el.appendChild(loader);
            },
            
            updateDOM(data) {
                const menuList = UIVariables.menuList;

                // Handle if there is no data form the database
                if (data.length === 0) {
                    this.messageDisplay(menuList, 'no results found');
                }

                // Handle if there is any data 
                if (data.length > 0) {
                    let compiledData = '';

                    data.forEach(menu => {
                        compiledData += `
                            <div class="col-lg-2 col-6 mb-4" id="card-container-${menu.id}" data-quantityleft="${menu.quantity}">
                                <a href="" data-toggle="modal" data-target="#ModalCenter${menu.id}">
                                    <div class="card product-card">
                                        <div class="product-image-box">`;
                        
                        if (menu.quantity <= 0) {
                            compiledData += `<span class="badge badge-danger food-added">OUT OF STOCK</span>`;
                        }

                        compiledData += `
                                    <img src="/images/menus/${menu.id}.${menu.image_extension}" class="card-img-top product-image" alt="${menu.name}">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-dark"><strong>${capitalizeWords(menu.name)}</strong></h5>
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                            <div class="food-price text-dark">&#8358; ${menu.price}</div>
                                            <span class="badge badge-info text-uppercase">${menu.category.name}</span>
                                        </div>
                                        `;
                        
                        if (menu.quantity > 0) {
                            compiledData += `
                            <button class="btn btn-sm btn-block btn-dark text-uppercase">
                                <i class="fa fa-plus-circle"></i> Add
                            </button>
                                `;
                            
                        }       
                        
                        compiledData += `     
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        `;

                        compiledData += this.cartModal(menu);
                    });

                    menuList.innerHTML = compiledData;
                }
            },

            cartModal(item) {
                let output = '';

                output += `
                    <div class="modal fade" id="ModalCenter${item.id}" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true" data-quantityleft="${item.quantity}">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content profile-pop-up">
                                <div class="modal-header">
                                    <div class="profile-summary">
                                        <h5>${capitalizeWords(item.name)}</h5>
                                    </div>
                                    <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true text-dark">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="profile-box mb-3">
                                        <img src="/images/menus/${item.id}.${item.image_extension}" class="img-fluid img-explorer-popup">
                                    </div>
                                    <div class="product-spec">
                                        <div class="p-stat">
                                            <p>Price(&#8358;)</p>
                                            <span>&#8358; ${item.price}</span>
                                        </div>
                                        <div class="p-stat">
                                            <p>Liters</p>
                                            <span>2.0</span>
                                        </div>
                                        <div class="p-stat">
                                            <p>Type</p>
                                            <span>${capitalizeWords(item.category.name)}</span>
                                        </div>
                                        <div class="p-stat">
                                            <p>Deliverable</p>
                                            <span>Yes</span>
                                        </div>
                                    </div>
                                    <p>${capitalizeFirst(item.description)}</p>                   
                                    <form class="add-to-cart-form" data-id="${item.id}">`;

                if (item.quantity > 0) {
                    output += `
                    <div class="quantity-count">
                        <div class="form-group row">
                            <label for="quantity-input-${item.id}" class="col-6 col-form-label">Quantity</label>
                            <div class="col-6">
                                <input type="number" class="form-control quantity-input" value="1" min="1" max="${item.quantity}" id="quantity-input-${item.id}">
                            </div>
                        </div>
                    </div>`;
                }

                output += `            
                                        <p class="text-center mt-3">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <button id="cart-btn-${item.id}" class="btn btn-main btn-block"${ item.quantity > 0 ? '' : ' disabled' }>${ item.quantity > 0 ? 'ADD TO CART' : 'OUT OF STOCK' }</button>
                                                </div>
                                            </div>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                return output;
            },
            
            messageDisplay(el, message, extraClass = undefined) {
                const h4 = document.createElement('h4');

                h4.classList.add('text-uppercase', 'text-center', 'pos-center', extraClass);

                h4.appendChild(document.createTextNode(message));

                el.appendChild(h4);
            },

            clearDOM(el) {
                while (el.firstChild) {
                    el.removeChild(el.firstChild);
                }
            },

            insertCheckoutMenu(menu, quantity) {
                const totals = menu.total;
                const data = menu.data;

                // Just brushing up the project
                if (totals !== null && data !== null) {
                    const ul = document.createElement('ul');
                    let checkout = '';

                    ul.classList.add('list-group', 'mb-3');

                    UIVariables.checkoutContainer.appendChild(ul);

                    data.forEach(item => {
                        checkout += `
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">${capitalizeWords(item.name)}</h6>
                                    <small class="text-muted">Quantity: ${quantity[`id${item.id}`]}</small>
                                </div>
                                <span class="text-muted">&#8358; ${totals.item_total[`id${item.id}`]}</span>
                            </li>
                        `;
                    });

                    // would put an appropriate check for the promo code later
                    // if (coupon) {
                    //     checkout += `
                    //         <li class="list-group-item d-flex justify-content-between bg-light">
                    //             <div class="text-success">
                    //                 <h6 class="my-0">Promo code</h6>
                    //                 <small>EXAMPLECODE</small>
                    //             </div>
                    //             <span class="text-success">-$5</span>
                    //         </li>
                    //     `;
                    // }

                    checkout += `
                        <li class="list-group-item d-flex justify-content-between">
                            <h6 class="my-0">Total</h6>
                            <strong>&#8358; ${totals.grand_total}</strong>
                        </li>
                    `;

                    ul.innerHTML = checkout;
                }
            }
        }

    })();

    // The Storage Controller. It handles everything concerning localStorage
    const Storage = (function() {
        const name = 'chrystacrispy_shopping_cart';

        return {

            getCart() {
                let cart;

                if (localStorage.getItem(name) === null) {
                    return [];
                } else {
                    cart = localStorage.getItem(name);

                    return JSON.parse(cart);
                }
            },

            destroyCart() {
                localStorage.removeItem(name);
            },

            setCart(cart) {
                const cartJson = JSON.stringify(cart);

                localStorage.setItem(name, cartJson);
            },

            getItem(id) {
                const cart = this.getCart();

                return cart.find(item => item.id === id);
            },

            setCartItem(item) {
                let cart = this.getCart();

                cart.push(item);

                // cart object to return
                cart = cart;

                const newCart = JSON.stringify(cart);

                localStorage.setItem(name, newCart);

                return cart;
            },

            removeFromCart(id) {
                const cart = this.getCart();

                const itemIndex = cart.findIndex(value => value.id === id);

                if (itemIndex !== -1) {
                    cart.splice(itemIndex, 1);

                    // update localStorage with new cart items
                    this.setCart(cart);
                }
            }
        }

    })();

    // The App Initializer. It brings the UI and Storage Controllers together
    const App = (function(ui, storage) {
        const categoryDropdown = ui.getUIVariables().categoryDropdown;
        const menuList = ui.getUIVariables().menuList;
        const cartCount = ui.getUIVariables().cartCount;
        const cartList = ui.getUIVariables().cartList;
        const modalHouse = ui.getUIVariables().modalHouse;
        const checkoutTotal = ui.getUIVariables().checkoutTotal;
        const checkoutContainer = ui.getUIVariables().checkoutContainer;
        const checkoutForm = ui.getUIVariables().checkoutForm;
        const checkoutErrorMessage = ui.getUIVariables().checkoutErrorMessage;
        const submitForms = ui.getUIVariables().submitForms;
        const loneForms = ui.getUIVariables().loneForms;
        const logoutButton = ui.getUIVariables().logoutButton;
        const navItems = ui.getUIVariables().navItems;

        function handleChangeCategory() {
            ui.clearDOM(menuList);

            ui.insertLoader(menuList);

            //Value of current menu list
            const value = categoryDropdown.value;

            //Check to make sure that the value is being given
            if (value !== "") {
                fetch(`/api/menu/${value}`)
                    // change result from json to JavaScript object
                    .then(res => res.json())

                    // handle data
                    .then(data => {
                        ui.clearDOM(menuList);

                        ui.updateDOM(data);

                        populateCart(storage.getCart());
                    })

                    // catch error. normally a user does not see this page
                    .catch((e) => {
                        ui.clearDOM(menuList);

                        console.log(e);
                    });
            }
        }

        function populateCart(cart) {
            getTotal();

            if (cart.length > 0) {
                // Show food added to cart badge
                cart.forEach(value => {
                    ui.addToCart(value);
                });
            }
        }

        function removeItem(e) {
            e.preventDefault();

            const button = e.target.closest('.cart-trash-form');

            if (button !== null) {
                // item row
                const row = button.parentElement.parentElement;

                // id of selected item
                const id = row.dataset.id;

                // modal of selected item
                const modal = document.getElementById(`ModalCenter${id}`);

                // remove item from cart
                storage.removeFromCart(id);

                // remove modal from page
                modalHouse.removeChild(modal);

                // update cart navigation
                getTotal();

                // refresh page with updated details
                loadCart();
            }
        }

        function alterCart(e) {
            e.preventDefault();

            // form of the menu item
            const form = e.target.closest('.add-to-cart-form');

            // check to make sure that form actually exists
            if (form !== null) {
                const id = form.dataset.id;

                const item = storage.getItem(id);

                const state = ui.getModalState()[`id${id}`];

                if (state === 'edit') {
                    // change the current modal state to update state
                    ui.setModalState(item, 'update');
                } else if (state === 'update') {
                    // update cart item with new values
                    const quantity = document.getElementById(`quantity-input-${item.id}`).value;

                    // reassign item quantity to new quantity
                    item.quantity = quantity;

                    // remove cart item with the id value and replace it with new values
                    storage.removeFromCart(item.id);

                    // set new item value to cart
                    storage.setCartItem(item);

                    // set modal state back to edit
                    ui.setModalState(item, 'edit');

                    // refresh cart with updated results
                    loadCart();
                }
            }
        }

        function removeFromCart(item) {
            storage.removeFromCart(item.id);

            ui.removeFromCart(item);
        }

        function addToCart(item) {
            // set to localStorage
            storage.setCartItem({ id: item.id, quantity: item.quantity });

            // pass item variables to the UI
            ui.addToCart(item);
        }

        function getTotal() {
            const cart = storage.getCart();
            
            // number of items in cart
            if (cartCount !== null) {
                cartCount.textContent = cart.length;
            }

            // this will run only in the checkout page
            if (checkoutTotal !== null) {
                checkoutTotal.textContent = cart.length;
            }
        }

        function toggleCart(e) {
            e.preventDefault();

            // form containing the item details
            const form = e.target.closest('.add-to-cart-form');

            if (form !== null) {
                const cart = storage.getCart();

                // id of menu item
                const id = form.dataset.id;

                // quantity selected by user
                const quantity = form.querySelector('.quantity-input').value;

                // check to see if the item has already been added to cart
                const check = cart.filter(current => current.id === id);

                if (check.length === 0) {
                    // Item not in cart so ADD TO CART
                    addToCart({ id, quantity });
                } else {
                    // item in cart so REMOVE FROM CART
                    removeFromCart({ id, quantity });
                }

                getTotal();
            }
        }

        function fetchMenuItems(cart, callback, errorContainer) {
            // fetching menu details from the database
            fetch('/api/menu', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(cart)
            })
            // change result from json to JavaScript object
            .then(res => res.json())

            // handle data
            .then(data => {
                callback(data);
            })

            // catch error. normally a user does not see this page
            .catch((e) => {
                console.log(e);
            });                
        }

        function loadCart() {
            const cart = storage.getCart();

            // check to see if any item is in the cart
            if (cart.length === 0) {
                ui.clearDOM(cartList);

                // cart is empty
                ui.messageDisplay(cartList, 'your cart is empty')
            } else {
                const table = document.getElementById('cart-items-table');
                // check to see if the table already exists in the page
                if (table === null) {
                    // insert loader to cart data is being loaded
                    ui.insertLoader(cartList);
                }

                fetchMenuItems(cart, data => {
                    ui.clearDOM(cartList);

                    // the user should never see this
                    if (data.data.length === 0 || data.data === null) {
                        ui.messageDisplay(cartList, 'no items in cart')
                    }

                    // don't need this but just to make sure the cart item really exist
                    if (data.data.length > 0) {
                        // making id as the key and the quantity as the value
                        const quantity = quantityToCartId(cart);

                        ui.loadCart(data, quantity, cart);
                    }
                }, cartList);
            } 
        }

        function quantityToCartId(cart) {
            const quantity = [];

            cart.forEach(item => quantity[`id${item.id}`] = item.quantity);

            return quantity;
        }

        function readyCheckout() {
            const cart = storage.getCart();

            // display when cart is empty
            if (cart.length === 0) {
                ui.messageDisplay(checkoutContainer, 'your cart is empty');
            }

            // run when cart contains items
            if (cart.length >= 1) {
                ui.insertLoader(checkoutContainer);

                fetchMenuItems(cart, data => {
                    const quantity = quantityToCartId(cart);

                    ui.clearDOM(checkoutContainer);

                    ui.insertCheckoutMenu(data, quantity);
                }, checkoutContainer);
            }
        }

        function processOrder(e) {
            e.preventDefault();

            const button = e.target.querySelector('button[type="submit"]');

            const oldText = button.textContent;
                
            const cart = storage.getCart();

            // Empty cart
            if (cart.length === 0) {
                if (!checkoutErrorMessage.firstElementChild) {
                    ui.messageDisplay(checkoutErrorMessage, 'your cart is empty', 'error');
                }

                setTimeout(() =>  ui.clearDOM(checkoutErrorMessage), 4000);
            }

            // Cart contains items
            if (cart.length > 0) {
                button.textContent = 'processing order...';
                button.disabled = true;

                fetch('/api/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(cart)
                })
                .then(res => res.json())

                .then(() => e.target.submit())

                .catch(e => {
                    // If all goes well, the user should never see this
                    if (!checkoutErrorMessage.firstElementChild) {
                        ui.messageDisplay(checkoutErrorMessage, 'an error occurred while processing your order. please try again', 'error');
                    }

                    setTimeout(() =>  ui.clearDOM(checkoutErrorMessage), 4000);
                    
                    // Enable the button back
                    button.disabled = false;
                    button.textContent = oldText;

                    console.log(e);
                });
            }
        }

        function handleLoneForms(e) {
            const button = e.target.querySelector('button[type="submit"]');
            
            button.disabled = true;
        }

        function handleSubmitForms(e) {
            const button = e.target.querySelector('button[type="submit"]');
            
            button.textContent = 'please wait...';
            button.disabled = true;
        }

        function handleLogout() {
            logoutButton.disabled = true;
        }

        function destroyCart() {
            storage.destroyCart();
        }

        function checkActiveLink() {
            // This can never be null because we will always have navigation links
            if (navItems !== null) {
                Array.from(navItems).forEach(navItem => {
                    const link = navItem.firstElementChild;

                    if (link !== null) {
                        const hrefLocation = link.getAttribute('href');

                        if (hrefLocation === location.pathname) {
                            navItem.classList.add('active');
                        }
                    }
                });
            }
        }
        
        function loadEventListeners() {
            // Event listener to destroy the cart
            if (location.pathname === '/order-complete') {
                document.addEventListener('DOMContentLoaded', destroyCart);
            }

            // Event listener to add the active class to navigation links
            document.addEventListener('DOMContentLoaded', checkActiveLink);

            /**
                Showing the display to the user of which item is in the cart.
                It includes the total amount of items in the cart, showing added to cart banner
                and also changing the modal to remove to cart
             */
            document.addEventListener('DOMContentLoaded', () => populateCart(storage.getCart()));

            // Event listener to destroy the cart
            if (location.pathname === '/order-complete') {
                document.addEventListener('DOMContentLoaded', destroyCart);
            }

            // Event listener for the cart page to show the items in the cart
            if (location.pathname === '/cart') {
                document.addEventListener('DOMContentLoaded', loadCart);
            }

            // Event listener for the checkout page to show the items in the cart
            if (location.pathname === '/checkout') {
                document.addEventListener('DOMContentLoaded', readyCheckout);
            }

            // Event that fires when the category dropdown is changed
            if (categoryDropdown !== null) {
                categoryDropdown.addEventListener('change', handleChangeCategory);
            }

            // Add cart item event listener
            if (menuList !== null) {
                menuList.addEventListener('submit', toggleCart);
            }

            // Event listener to delete the cart item
            if (cartList !== null) {
                cartList.addEventListener('submit', removeItem);
            }

            // Event listener to edit the cart
            if (modalHouse !== null) {
                modalHouse.addEventListener('submit', alterCart);
            }

            // Event listener for checkout form
            if (checkoutForm !== null) {
                checkoutForm.addEventListener('submit', processOrder);
            }

            // Event listener for the submit forms to disable the form when it is submitted
            if (submitForms !== null) {
                Array.from(submitForms).forEach(form => form.addEventListener('submit', handleSubmitForms));
            }

            // Event listener to disable buttons that have independent functions
            if (loneForms !== null) {
                Array.from(loneForms).forEach(button => button.addEventListener('submit', handleLoneForms))
            }

            // Event listener for the logout button
            if (logoutButton !== null) {
                logoutButton.parentElement.addEventListener('submit', handleLogout);
            }
        }

        return {

            init() {
                loadEventListeners();
            }

        }

    })(UI, Storage);

    // Application Initialization
    App.init();
})();