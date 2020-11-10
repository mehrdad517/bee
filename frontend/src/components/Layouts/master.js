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
        if (AppState.menu.ready !== 'success' || moment.unix() > AppState.setting.expiration) {
            dispatch(menu());
        }
    }, [AppState.menu.ready]);


    useEffect(() => {
        if (AppState.setting.ready !== 'success' || moment.unix() > AppState.setting.expiration) {
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
            <Login />
            <Drawer open={AppState.menu.drawer} onClose={() => dispatch({type: MENU_DRAWER, payload: false})}>
                <Sidebar />
            </Drawer>
        </div>
    );
}

export default Master;