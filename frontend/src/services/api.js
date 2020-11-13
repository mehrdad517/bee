import axios from 'axios';
import { toast } from 'react-toastify';
import Cookies from 'universal-cookie/lib';
import {ENV} from "../config/env";
import moment from "moment-jalaali";
let CryptoJS = require("crypto-js");

class Api {

    _headers() {

        let token = '';

        const cookies = new Cookies();
        if (cookies.get('auth')) {
            token = cookies.get('auth');
        }

        return {
            Accept: 'application/json',
            Authorization: `Bearer ${token}`
        };
    }

    // check response after receive
    _dispatchResponse(response) {
        if (typeof response !== 'undefined') {
            if (response.status === 401) {
                toast.error('مجددا وارد شوید.');

            } else if (response.status === 404) {
                toast.error('خطای سرور: سرویس مورد نظر دچار اختلال شده است.');
            } else {
                toast.error(response.statusText);
            }
        } else {
            toast.error('خطای سرور');
        }
    }

    _makeRequest(object) {

        let n_object = 'random:' + Math.random() + ';' +'time:' + moment().unix() + ';' + 'origin:' + window.location.host;

        let keys = Object.keys(object);

        keys.forEach((item) => {
            n_object += ";" + item + ':' + object[item]
        })

        let key = CryptoJS.enc.Hex.parse("c0ff70cc197a07dff1fb709688170426");
        let iv = CryptoJS.enc.Hex.parse("f8b4e45085a1045902c3c69c80e67a7c");

        let encrypted = CryptoJS.AES.encrypt(n_object, key, {
            iv,
            padding: CryptoJS.pad.ZeroPadding,
        });

        return encrypted.toString();
    }

    /**
     * ---------------------------------------------------------------
     *  GET POST PUT DELETE API
     * ---------------------------------------------------------------
     */
    get(url, object= {}) {
        return axios.get( ENV.API[window.location.host]+ `${url}`, {
            headers: this._headers(),
            params: {'request' : this._makeRequest(object)}
        }).then( (response) => {
            return response.data;
        }).catch((error) => {
            return this._dispatchResponse(error.response)
        })
    }

    post(url, object= {}) {
        return axios.post( ENV.API[window.location.host]+ `${url}`, {'request' : this._makeRequest(object)}, {
            headers: this._headers(),
        }).then( (response) => {
            return response.data;
        }).catch((error) => {
            return this._dispatchResponse(error.response)
        })
    }

    put(url, object={}) {
        return axios.put( ENV.API[window.location.host]+ `${url}`,  {'request' : this._makeRequest(object)},{
            headers: this._headers(),
        }).then( (response) => {
            return response.data;
        }).catch((error) => {
            return this._dispatchResponse(error.response)
        })
    }

    delete(url, object = {}) {
        return axios.delete( ENV.API[window.location.host]+ `${url}`, {
            params : {'request' : this._makeRequest(object)},
            headers: this._headers(),
        }).then((response) => {
            return response.data;
        }).catch((error) => {
            return this._dispatchResponse(error.response)
        })
    }


    upload(url, object= {}, options) {
        return axios.post( ENV.API[window.location.host]+ `${url}`, object, {
            options
        }).then( (response) => {
            return response.data;
        }).catch((error) => {
            return this._dispatchResponse(error.response)
        })
    }



}

export default Api;
