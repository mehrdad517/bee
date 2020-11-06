
// Export for unit testing
import {SETTING_FAILURE, SETTING_REQUESTING, SETTING_SUCCESS} from "../types";

export const initialState =  {
    ready: 'invalid',
    err: null,
    data: {}
};

export default (state = initialState, action) => {
    switch (action.type) {
        case SETTING_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case SETTING_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload
            };
        case SETTING_FAILURE:
            return {
                ...state,
                ready: 'failure',
                err: action.err
            };
        default:
            return state;
    }

    return state;
};
