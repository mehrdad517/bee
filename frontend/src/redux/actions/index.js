import Api from "../../services/api";
import {
    ADDRESS_FAILURE,
    ADDRESS_REQUESTING,
    ADDRESS_SUCCESS,
    BLOG_FAILURE,
    BLOG_REQUESTING,
    BLOG_SUCCESS,
    BRAND_FAILURE,
    BRAND_REQUESTING,
    BRAND_SUCCESS,
    CARD_FAILURE,
    CARD_REQUESTING,
    CARD_SUCCESS, MENU_FAILURE,
    MENU_REQUESTING, MENU_SUCCESS,
    POST_FAILURE,
    POST_REQUESTING,
    POST_SUCCESS, PRODUCT_FAILURE, PRODUCT_REQUESTING, PRODUCT_SUCCESS,
    PRODUCT_SWIPER_FAILURE,
    PRODUCT_SWIPER_REQUESTING,
    PRODUCT_SWIPER_SUCCESS,
    REGION_FAILURE,
    REGION_REQUESTING,
    REGION_SUCCESS,
    SETTING_FAILURE,
    SETTING_REQUESTING,
    SETTING_SUCCESS, SHOP_FAILURE, SHOP_REQUESTING, SHOP_SUCCESS,
    SLIDER_FAILURE,
    SLIDER_REQUESTING,
    SLIDER_SUCCESS
} from "../types";
import {toast} from "react-toastify";


export function card() {

    return function (dispatch) {
        dispatch({ type: CARD_REQUESTING });
        try {
            new Api().get('/card', {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: CARD_SUCCESS, payload: response});
                    toast.success('سبد خرید به روز رسانی گردید.');
                }
            })
        } catch (e) {
            dispatch({ type: CARD_FAILURE, err: e });
        }
    }

}

export function addToCart(object) {

    return function (dispatch) {

        dispatch({ type: CARD_REQUESTING });

        try {
            new Api().post('/card', object).then((response) => {
                if (typeof response !== "undefined") {
                    if (response.status) {
                        dispatch({ type: CARD_SUCCESS, payload: response.card});
                        toast.success('سبد خرید به روز رسانی گردید.');
                    } else {
                        dispatch({ type: CARD_FAILURE, err: response.msg });
                        toast.error(response.msg);
                    }
                }
            })
        } catch (e) {
            dispatch({ type: CARD_FAILURE, err: e });
            toast.error(e)
        }
    }
}

export function removeASCart(id) {

    return function (dispatch) {

        dispatch({ type: CARD_REQUESTING });

        try {
            new Api().delete('/card/' + id).then((response) => {
                if (typeof response !== "undefined") {
                    if (response.status) {
                        dispatch({ type: CARD_SUCCESS, payload: response.card});
                        toast.success('سبد خرید به روز رسانی گردید.');
                    } else {
                        dispatch({ type: CARD_FAILURE, err: response.msg });
                        toast.error(response.msg);
                    }
                }

            })
        } catch (e) {
            dispatch({ type: CARD_FAILURE, err: e });
            toast.error(e)
        }
    }
}

export function product(id) {
    return function (dispatch) {
        dispatch({ type: PRODUCT_REQUESTING });
        try {
            new Api().get('/shop/products/' + id, {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: PRODUCT_SUCCESS, payload: response, id: id});
                }
            })
        } catch (e) {
            dispatch({ type: PRODUCT_FAILURE, err: e });
        }
    }
}


export function shop(type, id, object) {
    return function (dispatch) {
        dispatch({ type: SHOP_REQUESTING });
        try {

            switch (type) {
                case 'shop':
                    new Api().get('/shop', object).then((response) => {
                        if (typeof response !== "undefined") {
                            dispatch({ type: SHOP_SUCCESS, payload: response});
                        }
                    });
                    break;
                case 'brand':
                    new Api().get('/shop/brand/' + id, object).then((response) => {
                        if (typeof response !== "undefined") {
                            dispatch({ type: SHOP_SUCCESS, payload: response});
                        }
                    });
                    break;
                case 'category':
                    new Api().get('/shop/category/' + id, object).then((response) => {
                        if (typeof response !== "undefined") {
                            dispatch({ type: SHOP_SUCCESS, payload: response});
                        }
                    });
                    break;
            }


        } catch (e) {
            dispatch({ type: SHOP_FAILURE, err: e });
        }
    }
}

export function menu() {
    return function (dispatch) {
        dispatch({ type: MENU_REQUESTING });
        try {
            new Api().get('/menu', {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: MENU_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: MENU_FAILURE, err: e });
        }
    }
}

export function post(id) {
    return function (dispatch) {
        dispatch({ type: POST_REQUESTING });
        try {
            new Api().get('/blog/content/' + id, {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: POST_SUCCESS, payload: response, id: id});
                }
            })
        } catch (e) {
            dispatch({ type: POST_FAILURE, err: e });
        }
    }
}


export function region() {

    return function (dispatch) {

        dispatch({ type: REGION_REQUESTING });

        try {
            new Api().get('/region', {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: REGION_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: REGION_FAILURE, err: e });
        }


    }

}



export function address() {

    return function (dispatch) {

        dispatch({ type: ADDRESS_REQUESTING });

        try {
            new Api().get('/address', {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: ADDRESS_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: ADDRESS_FAILURE, err: e });
        }


    }

}





export function setting() {

    return function (dispatch) {
        dispatch({ type: SETTING_REQUESTING });
        try {
            new Api().get('/setting', {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: SETTING_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: SETTING_FAILURE, err: e });
        }
    }

}


export function blogCategories(id, object) {

    return function (dispatch) {

        dispatch({ type: BLOG_REQUESTING });

        try {
            new Api().get('/blog/category/' + id, object).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: BLOG_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: BLOG_FAILURE, err: e });
        }
    }

}

export function blog(object) {

    return function (dispatch) {

        dispatch({ type: BLOG_REQUESTING });

        try {
            new Api().get('/blog', object).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: BLOG_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: BLOG_FAILURE, err: e });
        }
    }

}


export function productSwiper() {

    return function (dispatch) {

        dispatch({ type: PRODUCT_SWIPER_REQUESTING });

        try {
            new Api().get('/shop/products/swiper', {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: PRODUCT_SWIPER_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: PRODUCT_SWIPER_FAILURE, err: e });
        }
    }

}

export function slider() {

    return function (dispatch) {

        dispatch({ type: SLIDER_REQUESTING });

        try {
            new Api().get('/slider', {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: SLIDER_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: SLIDER_FAILURE, err: e });
        }
    }

}

export function brand() {

    return function (dispatch) {

        dispatch({ type: BRAND_REQUESTING });

        try {
            new Api().get('/brand', {}).then((response) => {
                if (typeof response !== "undefined") {
                    dispatch({ type: BRAND_SUCCESS, payload: response});
                }
            })
        } catch (e) {
            dispatch({ type: BRAND_FAILURE, err: e });
        }
    }

}