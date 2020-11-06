import React from 'react'
import {ToastContainer} from "react-toastify";
import 'react-toastify/dist/ReactToastify.css';
import {Provider} from "react-redux";
import store from './redux/store';
import { BrowserRouter as Router, Route, Switch, Link } from 'react-router-dom';
import Home from "./pages";
import NoMatch from "./components/NoMatch";
import Master from "./components/Layouts/master";
import './assets/fonts/vazir/font.css'
import './assets/css/global.css'


function App() {
    return (
        <Provider store={store}>
            <Router>
                <Master>
                    <Switch>
                        <Route exact path="/" component={Home} />
                        <NoMatch/>
                    </Switch>
                </Master>
            </Router>
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
        </Provider>
    );
}

export default App;
