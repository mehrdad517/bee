import moment from "moment-jalaali";
import {SHOP_FAILURE, SHOP_REQUESTING, SHOP_SUCCESS} from "../types";

export const initialState = {
    ready: 'invalid',
    err: null,
    data: [],
    expiration: moment.unix()
};

export default (state = initialState, action) => {
    switch (action.type) {
        case SHOP_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case SHOP_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload,
                expiration: moment().add(1, 'hours').unix(),
            };
        case SHOP_FAILURE:
            return {
                ...state,
                ready: 'failure',
                err: action.err
            };
        default:
            return state;
    }
};
