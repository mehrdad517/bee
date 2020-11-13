import {PRODUCT_FAILURE, PRODUCT_REQUESTING, PRODUCT_SUCCESS} from "../types";

export const initialState = null

export default (state = initialState, action) => {

    switch (action.type) {
        case PRODUCT_REQUESTING:
            return {
                ...state,
                [action.id]: {
                    ready: 'request',
                    data: action.payload
                }
            };
        case PRODUCT_SUCCESS:
            return {
                ...state,
                [action.id]: {
                    ready: 'success',
                    data: action.payload
                }
            };
        case PRODUCT_FAILURE:
            return {
                ...state,
                [action.id]: {
                    ready: 'failure',
                    error: action.payload
                }
            };
        default:
            return state;
    }

};
