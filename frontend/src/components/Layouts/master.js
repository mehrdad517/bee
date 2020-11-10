import React, {useEffect} from 'react';
import {connect, useDispatch, useSelector} from "react-redux";
import Header from "../Header";
import {card, setting} from "../../redux/actions";
import Footer from "../Footer";
import Api from "../../services/api";
let CryptoJS = require("crypto-js");

const Master = (props) => {

    const { children } = props;

    const dispatch = useDispatch();
    const  AppState  = useSelector(state => state);

    useEffect(() => {

        // let data = "test"
        // let key = "56105610"
        // let IV = "p/34qWLcYcg="
        // let cipher = CryptoJS.TripleDES.encrypt(data, CryptoJS.enc.Utf8.parse(key), {
        //     iv: CryptoJS.enc.Utf8.parse(IV),
        //     mode: CryptoJS.mode.CBC
        // });
        //
        // console.log(cipher.toString())
        //
        // new Api().get('/setting', {rq: CryptoJS.DES.encrypt('mehrdad', '56105610',).toString()}).then((response) => {
        //     console.log(response)
        //
        // })
    }, []);

    useEffect(() => {
        if (AppState.setting.ready !== 'success') {
            dispatch(setting());
        }
    }, [AppState.setting.ready]);

    useEffect(() => {
        if (AppState.card.ready !== 'success' && AppState.auth.login) {
            dispatch(card());
        }
    }, [AppState.card.ready]);


    return(
        <div className={'master'}>
            <Header />
            <div className={'master-inner'}>
                {children}
            </div>
            <Footer/>
        </div>
    );
}

export default Master;