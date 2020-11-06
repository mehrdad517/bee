import {PRODUCT_SWIPER_FAILURE, PRODUCT_SWIPER_REQUESTING, PRODUCT_SWIPER_SUCCESS} from "../types";

export const initialState = {
    readyStatus: 'invalid',
    err: null,
    data: []
};

export default (state = initialState, action) => {
    switch (action.type) {
        case PRODUCT_SWIPER_REQUESTING:
            return {
                ...state,
                readyStatus: 'request'
            };
        case PRODUCT_SWIPER_SUCCESS:
            return {
                ...state,
                readyStatus: 'success',
                data: action.payload
            };
        case PRODUCT_SWIPER_FAILURE:
            return {
                ...state,
                readyStatus: 'failure',
                err: action.err
            };
        default:
            return state;
    }
};
