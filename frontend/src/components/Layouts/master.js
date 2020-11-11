import React, {useEffect} from 'react';
import {connect, useDispatch, useSelector} from "react-redux";
import Header from "../Header";
import {card, menu, setting} from "../../redux/actions";
import Footer from "../Footer";
import moment from "moment-jalaali";
import Login from "../Auth/Login";
import Drawer from '@material-ui/core/Drawer';
import {MENU_DRAWER} from "../../redux/types";
import Sidebar from "../Sidebar";

const Master = (props) => {

    const { children } = props;

    const dispatch = useDispatch();
    const  AppState  = useSelector(state => state);



    useEffect(() => {

        if (AppState.card.ready !== 'success' && AppState.auth.login) {
            dispatch(card());
        }

        if (AppState.setting.ready !== 'success' || moment.unix() > AppState.setting.expiration) {
            dispatch(setting());
        }

        if (AppState.menu.ready !== 'success' || moment.unix() > AppState.setting.expiration) {
            dispatch(menu());
        }


    }, []);



    return(
        <div className={'master'}>
            <Header />
            <div className={'master-inner'} style={{ minHeight: 800}}>
                {children}
            </div>
            <Footer/>
            <Login />
            <Drawer open={AppState.menu.drawer} onClose={() => dispatch({type: MENU_DRAWER, payload: false})}>
                <Sidebar />
            </Drawer>
        </div>
    );
}

export default Master;