
// Export for unit testing
import {BRAND_FAILURE, BRAND_REQUESTING, BRAND_SUCCESS} from "../types";
import moment from "moment-jalaali";

export const initialState  = {
    ready: 'invalid',
    err: null,
    data: [],
    expiration: moment.unix()
};

export default (state = initialState, action) => {
    switch (action.type) {
        case BRAND_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case BRAND_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload,
                expiration: moment().add(1, 'hours').unix(),
            };
        case BRAND_FAILURE:
            return {
                ...state,
                ready: 'failure',
                err: action.err
            };
        default:
            return state;
    }
};
