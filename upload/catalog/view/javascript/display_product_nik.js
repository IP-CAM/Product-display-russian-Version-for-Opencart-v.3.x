$(document).ready(function () {
    $('.fa-heart').parents('button').on('click', function () {
        let wishlist = getCookie("wishlist")
        let onclickAttr = $(this).attr('onclick');
        let productId = onclickAttr.split("'")[1]
        let cookieVal = [];
        if (!wishlist) {
            cookieVal.push(productId)
            setCookie('wishlist', cookieVal, {'max-age': 3600});
        } else {
            cookieVal = wishlist.split(',')
            if(!cookieVal.includes(productId)) {
                cookieVal.push(productId)
            } else {
                const index = cookieVal.indexOf(productId);
                if (index > -1) {
                    cookieVal.splice(index, 1);
                }
            }
            setCookie('wishlist', cookieVal, {'max-age': 3600});
        }
    })

    $('.product-thumb .image a').on('click', function (event) {
        setProductViewed($(this))
    })

    $('.product-thumb .caption h4 a').on('click', function (event) {
        setProductViewed($(this))
    })

    function setProductViewed(that) {
        let viewed_products = getCookie("viewed_products")
        let onclickAttr = $($(that).parents('.product-thumb').find('button')[0]).attr('onclick');
        let productId = onclickAttr.split("'")[1]
        let cookieVal = [];
        if (!viewed_products) {
            cookieVal.push(productId)
            setCookie('viewed_products', cookieVal, {'max-age': 3600});
        } else {
            cookieVal = viewed_products.split(',')
            if(!cookieVal.includes(productId)) {
                cookieVal.push(productId)
            }
            setCookie('viewed_products', cookieVal, {'max-age': 3600});
        }
        console.log('added')
    }

// возвращает куки с указанным name,
// или undefined, если ничего не найдено
    function getCookie(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    function setCookie(name, value, options = {}) {

        options = {
            path: '/',
            // при необходимости добавьте другие значения по умолчанию
            ...options
        };

        if (options.expires instanceof Date) {
            options.expires = options.expires.toUTCString();
        }

        let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

        for (let optionKey in options) {
            updatedCookie += "; " + optionKey;
            let optionValue = options[optionKey];
            if (optionValue !== true) {
                updatedCookie += "=" + optionValue;
            }
        }

        document.cookie = updatedCookie;
    }

// Пример использования:
    setCookie('user', 'John', {secure: true, 'max-age': 3600});


    function deleteCookie(name) {
        setCookie(name, "", {
            'max-age': -1
        })
    }
})