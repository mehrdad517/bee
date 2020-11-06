
// Export for unit testing
import {SLIDER_FAILURE, SLIDER_REQUESTING, SLIDER_SUCCESS} from "../types";

export const initialState = {
    ready: 'invalid',
    err: null,
    data: []
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
                data: action.payload
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
