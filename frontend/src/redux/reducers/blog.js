import {BLOG_FAILURE, BLOG_REQUESTING, BLOG_SUCCESS} from "../types";
import moment from "moment-jalaali";

export const initialState = {
    ready: 'invalid',
    err: null,
    data: [],
    expiration: moment.unix()
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
                data: action.payload,
                expiration: moment().add(1, 'hours').unix(),
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
