import {
    MENU_FAILURE,
    MENU_REQUESTING,
    MENU_SUCCESS,
    MENU_CHANGE_BLOG_EXPANDED,
    MENU_CHANGE_CATEGORY_EXPANDED,
    MENU_CHANGE_FOOTER_EXPANDED,
    MENU_CHANGE_MENU_EXPANDED, MENU_DRAWER
} from '../types';
import moment from "moment-jalaali";

// Export for unit testing
export const initialState =  {
    ready: 'invalid',
    err: null,
    menuExpanded: [],
    blogExpanded: [],
    footerExpanded: [],
    categoryExpanded: [],
    data: [],
    expiration: moment.unix(),
    drawer: false
};

export default (state = initialState, action) => {
    switch (action.type) {
        case MENU_DRAWER:
            return {
                ...state,
                drawer: action.payload
            };
        case MENU_REQUESTING:
            return {
                ...state,
                ready: 'request'
            };
        case MENU_SUCCESS:
            return {
                ...state,
                ready: 'success',
                data: action.payload,
                expiration: moment().add(1, 'hours').unix(),
            };
        case MENU_FAILURE:
            return {
                ...state,
                ready: 'failure',
                err: action.err
            };
        case MENU_CHANGE_CATEGORY_EXPANDED:
            return {
                ...state,
                categoryExpanded: action.payload
            };
        case MENU_CHANGE_MENU_EXPANDED:
            return {
                ...state,
                menuExpanded: action.payload
            };
        case MENU_CHANGE_FOOTER_EXPANDED:
            return {
                ...state,
                footerExpanded: action.payload
            };
        case MENU_CHANGE_BLOG_EXPANDED:
            return {
                ...state,
                blogExpanded: action.payload
            };
        default:
            return state;
    }

    return state;
};
