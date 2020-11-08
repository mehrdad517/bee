import axios from 'axios';
import { toast } from 'react-toastify';
import Cookies from 'universal-cookie/lib';
import {ENV} from "../config/env";

class Api {

    headers() {

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
    dispatchResponse(response) {
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

    /**
     * ---------------------------------------------------------------
     *  GET POST PUT DELETE API
     * ---------------------------------------------------------------
     */
    get(url, object= {}) {
        return axios.get( ENV.API[window.location.host]+ `${url}`, {
            headers: this.headers(),
            params: object
        }).then( (response) => {
            return response.data;
        }).catch((error) => {
            return this.dispatchResponse(error.response)
        })
    }

    post(url, object= {}) {
        return axios.post( ENV.API[window.location.host]+ `${url}`, object, {
            headers: this.headers(),
        }).then( (response) => {
            return response.data;
        }).catch((error) => {
            return this.dispatchResponse(error.response)
        })
    }

    put(url, object={}) {
        return axios.put( ENV.API[window.location.host]+ `${url}`,  object,{
            headers: this.headers(),
        }).then( (response) => {
            return response.data;
        }).catch((error) => {
            return this.dispatchResponse(error.response)
        })
    }

    delete(url, object) {
        return axios.delete( ENV.API[window.location.host]+ `${url}`, {
            params : object,
            headers: this.headers(),
        }).then((response) => {
            return response.data;
        }).catch((error) => {
            return this.dispatchResponse(error.response)
        })
    }


    upload(url, object= {}, options) {
        return axios.post( ENV.API[window.location.host]+ `${url}`, object, {
            options
        }).then( (response) => {
            return response.data;
        }).catch((error) => {
            return this.dispatchResponse(error.response)
        })
    }



}

export default Api;
