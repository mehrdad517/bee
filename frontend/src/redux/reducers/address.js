
// Export for unit testing
import {ADDRESS_FAILURE, ADDRESS_REQUESTING, ADDRESS_SUCCESS} from "../types";

export const initialState =  {
    ready: 'invalid',
    err: null,
    data: []
};

export default (state = initialState, action) => {
    switch (action.type) {
        case ADDRESS_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case ADDRESS_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload
            };
        case ADDRESS_FAILURE:
            return {
                ...state,
                ready: 'success',
                err: action.err
            };
        default:
            return state;
    }

    return state;
};
