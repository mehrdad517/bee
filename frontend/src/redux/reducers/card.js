

// Export for unit testing
import {CARD_FAILURE, CARD_REQUESTING, CARD_SUCCESS} from "../types";

export const initialState =  {
    ready: 'invalid',
    err: null,
    data: []
};

export default (state = initialState, action) => {
    switch (action.type) {
        case CARD_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case CARD_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload
            };
        case CARD_FAILURE:
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
