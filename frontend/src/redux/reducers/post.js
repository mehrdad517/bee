import {POST_FAILURE, POST_REQUESTING, POST_SUCCESS} from "../types";

export const initialState = null

export default (state = initialState, action) => {

    switch (action.type) {
        case POST_REQUESTING:
            return {
                ...state,
                [action.id]: {
                    ready: 'request',
                    data: action.payload
                }
            };
        case POST_SUCCESS:
            return {
                ...state,
                [action.id]: {
                    ready: 'success',
                    data: action.payload
                }
            };
        case POST_FAILURE:
            return {
                ...state,
                [action.id]: {
                    ready: 'failure',
                    data: action.payload
                }
            };
        default:
            return state;
    }

};
