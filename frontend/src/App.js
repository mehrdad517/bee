import React from 'react'
import {ToastContainer} from "react-toastify";
import 'react-toastify/dist/ReactToastify.css';
import {Provider} from "react-redux";
import { BrowserRouter as Router, Route, Switch, Link } from 'react-router-dom';
import Home from "./pages";
import NoMatch from "./components/NoMatch";
import Master from "./components/Layouts/master";
import './assets/fonts/vazir/font.css'
import './assets/css/global.css'
import Login from "./components/Auth/Login";
import { createMuiTheme, MuiThemeProvider } from '@material-ui/core/styles';
import { create } from 'jss';
import rtl from 'jss-rtl';
import { StylesProvider, jssPreset } from '@material-ui/core/styles';
import { PersistGate } from 'redux-persist/integration/react'
import configureStore  from './../src/redux/store'
import Card from "./pages/order/card";
import Invoice from "./pages/order/invoice";
import {Post} from "./pages/blog/post";
import {Blog} from "./pages/blog";
import Shop from "./pages/shop";
import Product from "./pages/product";

const theme = createMuiTheme({
    direction: 'rtl',
    palette: {
        primary: {
            main: '#263238'
        },
        secondary: {
            main: '#fada36'
        }
    },
    overrides: {
        MuiListItem: {
            root: {
                paddingTop: '4px',
                paddingBottom: '4px'
            }
        },
        MuiTypography: {
            root: {
                fontFamily: 'vazir !important',
                fontSize: '13px !important'
            }
        },
        MuiChip: {
            root: {
                marginLeft: 3
            }
        }
    },
});
const jss = create({ plugins: [...jssPreset().plugins, rtl()] });

const {store, persistor} = configureStore();

function App() {
    return (
        <Provider store={store}>
            <PersistGate loading={null} persistor={persistor}>
                <StylesProvider jss={jss}>
                    <MuiThemeProvider theme = { theme }>
                        <Router>
                            <Switch>
                                <Route exact path="/invoice/:id" component={Invoice} />
                                <Master>
                                    <Route exact path="/" component={Home} />
                                    <Route exact path="/card" component={Card} />
                                    {/* product and shop */}
                                    <Route exact path="/shop" component={Shop} />
                                    <Route exact path="/product/:id/:slug?" component={Product} />
                                    <Route exact path="/category/:id/:slug?" component={Shop} />
                                    <Route exact path="/brand/:id/:slug?" component={Shop} />
                                    {/* blog and post   */}
                                    <Route exact path="/blog" component={Blog} />
                                    <Route exact path="/blog/:id/:slug?" component={Blog} />
                                    <Route exact path="/post/:id/:slug?" component={Post} />
                                    <Route exact path="/page/:id" component={Post} />
                                </Master>
                                <NoMatch/>
                            </Switch>
                        </Router>
                    </MuiThemeProvider>
                </StylesProvider>
                <ToastContainer
                    position="bottom-center"
                    autoClose={5000}
                    hideProgressBar={true}
                    newestOnTop={false}
                    closeOnClick
                    rtl
                    pauseOnVisibilityChange
                    draggable={false}
                    pauseOnHover />
            </PersistGate>
        </Provider>
    );
}

export default App;
