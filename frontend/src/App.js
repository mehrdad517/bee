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

const theme = createMuiTheme({
    direction: 'rtl',
    palette: {
        primary: {
            main: '#000'
        },
        secondary: {
            main: '#fecc00'
        }
    },
    typography: {
        fontFamily: 'vazir',
        fontSize: 13
    },
    overrides: {
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
                                    {/* blog and post   */}
                                    <Route exact path="/blog/post/:id/:slug?" component={Post} />
                                    <Route exact path="/page/:id" component={Post} />
                                </Master>
                                <NoMatch/>
                            </Switch>
                            <Login />
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
