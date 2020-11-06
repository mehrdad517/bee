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

const Header = () => {

    const [anchorEl, setAnchorEl] = useState(false); // menu material

    const AppState = useSelector(state => state);


    const dispatch = useDispatch();
    const history = useHistory();
    useEffect(()=>{
        // let HeaderTop = document.querySelector(".Header-top")
        // window.addEventListener("scroll",()=>{
        //     if(scrollY > 50){
        //         HeaderTop.classList.add("fixing")
        //     }else if(scrollY < 50){
        //         HeaderTop.classList.remove("fixing")
        //     }
        // })
    },[])
    // @ts-ignore
    return(
        <>
            {AppState.settingReducers.ready === 'success' && <div className="Header">
                <div className="Header-top">
                    <Container>
                        <div className={'header-inner'}>
                            <div className="Header-top-right">
                                <div className="HeadMenu">
                                    {/*<img width={130} height={50} src={logo} />*/}
                                    <div className="Menu">
                                        <div className="Menubtn" >
                                            <span>منوی سایت</span>
                                            <img src={(AppState.settingReducers.data.backend + '/static/img/menu.png')}/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="HeadLogo">
                                <Link to={'/'}>
                                    <img  src={logo} />
                                </Link>
                            </div>
                            <div className="HeadProfile">
                                <img src={searchIcon} className={'search-btn'}/>
                                <div className="header-account">
                                    <img src={accountIcon} className="jsx-2982413346" />
                                    <span className="jsx-2982413346">  حساب کاربری</span>
                                </div>
                                <div className="header-account">
                                    <img src={cartIcon} className="jsx-2982413346" />
                                    <span className="jsx-2982413346">سبد خرید</span>
                                </div>
                            </div>
                        </div>

                    </Container>
                </div>
                {/* drawer  */}
                <div className='drawer' style={{ right: (AppState.settingReducers.drawer ? '0' : '-300px')}}>
                    {/*<Sidebar onClose={() => dispatch({type: SETTING_TOGGLE_DRAWER, payload: false})} />*/}
                </div>
                {/*<Backdrop style={{ zIndex: 999}}  open={AppState.settingReducers.drawer} onClick={() => dispatch({type: SETTING_TOGGLE_DRAWER, payload: false})} />*/}
            </div>}
        </>
    );
};



export default Header
