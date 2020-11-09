import React, {memo, useEffect, useState} from "react";
import {
    Container,
    Grid,
} from '@material-ui/core';
import Button from '@material-ui/core/Button';
import Typography from '@material-ui/core/Typography';
import PermIdentityIcon from '@material-ui/icons/PermIdentity';
import Badge from '@material-ui/core/Badge';
import ShoppingCartIcon from '@material-ui/icons/ShoppingCart';
import IconButton from '@material-ui/core/IconButton';
import {Link, useHistory} from 'react-router-dom';
import Menu from '@material-ui/core/Menu';
import MenuItem from '@material-ui/core/MenuItem';
import './style.css'
import Backdrop from '@material-ui/core/Backdrop';
import {connect, useDispatch, useSelector} from "react-redux";
import Fade from "@material-ui/core/Fade";
import {isBrowser, isMobile, isMobileOnly} from "react-device-detect";
import accountIcon from './../../assets/img/account.png'
import cartIcon from './../../assets/img/cart.png'
import searchIcon from './../../assets/img/search.png'
import logo from './../../assets/img/logo-min.png'
import {AUTH_DIALOG} from "../../redux/types";
import App from "../../App";

const Header = () => {

    const AppState = useSelector(state => state);


    const dispatch = useDispatch();
    const history = useHistory();

    return(
        <>
            {AppState.setting.ready === 'success' && <div className="Header">
                <div className={'header-info'}>
                    <Container>
                        <div className={'header-info-inner'}>
                            <p>بی نتورک, بهترین انتخاب استثنایی به شباهت زنبور</p>
                            <ul>
                                <li>021-44900500</li>
                                <li>Info@bee.ir</li>
                            </ul>
                        </div>
                    </Container>
                </div>
                <div className="Header-top">
                    <Container>
                        <div className={'header-inner'}>
                            <div className="Header-top-right">
                                <div className="HeadMenu">
                                    <div className="Menu">
                                        <div className="Menubtn" >
                                            <span>منوی سایت</span>
                                            <img src={(AppState.setting.data.backend + '/static/img/menu.png')}/>
                                        </div>
                                    </div>
                                </div>
                                <div className="HeadLogo">
                                    <Link to={'/'}>
                                        <img  src={logo} />
                                    </Link>
                                </div>
                            </div>
                            <div className="HeadProfile">
                                <img src={searchIcon} className={'search-btn'}/>
                                <div>
                                    <div className="header-account" onClick={() => dispatch({type: AUTH_DIALOG, payload: true})}>
                                        <img src={accountIcon} className="jsx-2982413346" />
                                        <span className="jsx-2982413346">{AppState.auth.login ? AppState.auth.user.mobile : 'حساب کاربری'}</span>
                                    </div>
                                    <div className="header-account" onClick={() => history.push('/card')}>
                                        <span className="Header_counter">{AppState.card.data.length > 0 ? AppState.card.data.length : 0}</span>
                                        <img src={cartIcon} className="jsx-2982413346" />
                                        <span className="jsx-2982413346">سبد خرید</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </Container>
                </div>
                {/* drawer  */}
                <div className='drawer' style={{ right: (AppState.setting.drawer ? '0' : '-300px')}}>
                    {/*<Sidebar onClose={() => dispatch({type: SETTING_TOGGLE_DRAWER, payload: false})} />*/}
                </div>
                {/*<Backdrop style={{ zIndex: 999}}  open={AppState.setting.drawer} onClick={() => dispatch({type: SETTING_TOGGLE_DRAWER, payload: false})} />*/}
            </div>}
        </>
    );
};



export default Header
