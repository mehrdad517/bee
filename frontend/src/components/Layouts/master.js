import React, {useEffect} from 'react';
import {connect, useDispatch, useSelector} from "react-redux";
import Header from "../Header";
import {setting} from "../../redux/actions";
import Footer from "../Footer";


const Master = (props) => {

    const { children } = props;

    const dispatch = useDispatch();
    const  AppState  = useSelector(state => state);

    useEffect(() => {
        if (AppState.settingReducers.ready !== 'success') {
            dispatch(setting());
        }
    }, [AppState.settingReducers.ready]);


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