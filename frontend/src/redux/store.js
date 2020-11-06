import { createStore , applyMiddleware} from 'redux';
import thunk from "redux-thunk";
import promise from 'redux-promise';
import rootReducer from './reducers';
import { composeWithDevTools } from 'redux-devtools-extension';

const store = createStore(rootReducer, composeWithDevTools(applyMiddleware(
    thunk,
    promise
),));


export default store;