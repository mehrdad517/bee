import { createStore , applyMiddleware} from 'redux';
import thunk from "redux-thunk";
import promise from 'redux-promise';
import rootReducer from './reducers';
import { composeWithDevTools } from 'redux-devtools-extension';
import storage from 'redux-persist/lib/storage' // defaults to localStorage for web
import { persistStore, persistReducer } from 'redux-persist'

const persistConfig = {
    key: 'root',
    storage,
}

const persistedReducer = persistReducer(persistConfig, rootReducer)



export default () => {
    let store = createStore(persistedReducer, composeWithDevTools(applyMiddleware(
        thunk,
        promise
    )));
    let persistor = persistStore(store)
    return { store, persistor }
}
