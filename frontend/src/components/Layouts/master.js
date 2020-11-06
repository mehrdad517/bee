import React, {useEffect} from 'react';
import {connect, useDispatch, useSelector} from "react-redux";
import Header from "../Header";
import {setting} from "../../redux/actions";
import Footer from "../Footer";


const Master = (props) => {

    const { children } = props;

    const dispatch = useDispatch();
    const { data } = useSelector(state => state.settingReducers);

    useEffect(() => {
        if (data.ready !== 'success') {
            dispatch(setting());
        }
    }, []);


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