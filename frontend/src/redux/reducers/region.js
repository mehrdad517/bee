import {REGION_FAILURE, REGION_REQUESTING, REGION_SUCCESS} from "../types";

export const initialState =  {
    ready: 'invalid',
    err: null,
    data: []
};

export default (state = initialState, action) => {
    switch (action.type) {
        case REGION_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case REGION_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload
            };
        case REGION_FAILURE:
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
