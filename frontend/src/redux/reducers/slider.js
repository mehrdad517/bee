
// Export for unit testing
import {SLIDER_FAILURE, SLIDER_REQUESTING, SLIDER_SUCCESS} from "../types";
import moment from "moment-jalaali";

export const initialState = {
    ready: 'invalid',
    err: null,
    data: [],
    expiration: moment.unix()
};

export default (state = initialState, action) => {
    switch (action.type) {
        case SLIDER_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case SLIDER_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload,
                expiration: moment().add(1, 'hours').unix(),
            };
        case SLIDER_FAILURE:
            return {
                ...state,
                ready: 'failure',
                err: action.err
            };
        default:
            return state;
    }
};
