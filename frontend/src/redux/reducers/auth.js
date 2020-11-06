import Cookies from "universal-cookie";
import {AUTH_DIALOG, AUTH_LOGIN, AUTH_LOGOUT, AUTH_REFRESH} from "../types";
import  moment from "moment";


export const initialState = {
    login: false,
    token: null,
    user: null,
    dialog: false
};

const cookies = new Cookies();

export default (state = initialState, action) => {
    switch (action.type) {
        case AUTH_LOGIN:
            cookies.set('auth', action.payload.token, { path: '/', expires: moment().add(1, "year").toDate() });
            return {
                ...state,
                login: true,
                user: action.payload.user,
                token: action.payload.token
            };
        case AUTH_LOGOUT:
            cookies.remove('auth');
            return {
                ...state,
                user: null,
                token: null,
                login: false
            };
        case AUTH_REFRESH:
            return {
                ...state,
                user: action.payload
            }
        case AUTH_DIALOG:
            return {
                ...state,
                dialog: action.payload
            }
        default:
            return state;
    }
};
