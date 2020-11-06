import {BLOG_FAILURE, BLOG_REQUESTING, BLOG_SUCCESS} from "../types";

export const initialState = {
    ready: 'invalid',
    err: null,
    data: []
};

export default (state = initialState, action) => {
    switch (action.type) {
        case BLOG_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case BLOG_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload
            };
        case BLOG_FAILURE:
            return {
                ...state,
                ready: 'failure',
                err: action.err
            };
        default:
            return state;
    }
};
