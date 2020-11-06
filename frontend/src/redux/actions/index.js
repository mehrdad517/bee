import Api from "../../services/api";
import {
    BLOG_FAILURE,
    BLOG_REQUESTING,
    BLOG_SUCCESS, BRAND_FAILURE,
    BRAND_REQUESTING,
    BRAND_SUCCESS,
    PRODUCT_SWIPER_FAILURE,
    PRODUCT_SWIPER_REQUESTING,
    PRODUCT_SWIPER_SUCCESS,
    SETTING_FAILURE,
    SETTING_REQUESTING,
    SETTING_SUCCESS, SLIDER_FAILURE, SLIDER_REQUESTING, SLIDER_SUCCESS
} from "../types";

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


export function blog() {

    return function (dispatch) {

        dispatch({ type: BLOG_REQUESTING });

        try {
            new Api().get('/blog', { page: 1, limit: 4 }).then((response) => {
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