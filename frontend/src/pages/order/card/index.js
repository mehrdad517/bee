import React, {useEffect, memo, useState} from 'react';
import {connect, useDispatch, useSelector} from 'react-redux';
import {toast} from "react-toastify";
import Container from "@material-ui/core/Container";
import {useHistory} from "react-router";
import {Link} from "react-router-dom";
import IconButton from '@material-ui/core/IconButton';
import DeleteIcon from '@material-ui/icons/Delete';
import TextField from '@material-ui/core/TextField';
import CurrencyFormat from 'react-currency-format';
import AddCircleIcon from '@material-ui/icons/AddCircle';
import RemoveCircleIcon from '@material-ui/icons/RemoveCircle';
import RemoveIcon from '@material-ui/icons/Remove';
import AddIcon from '@material-ui/icons/Add';
import Stepper from "@material-ui/core/Stepper";
import Step from "@material-ui/core/Step";
import StepLabel from "@material-ui/core/StepLabel";
import Hidden from "@material-ui/core/Hidden";
import Grid from "@material-ui/core/Grid";
import Button from '@material-ui/core/Button';
import ArrowForwardIcon from '@material-ui/icons/ArrowForward';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import Radio from '@material-ui/core/Radio';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import Helmet from 'react-helmet'
import {
    ADDRESS_DIALOG,
    ADDRESS_SUCCESS,
    CARD_FAILURE,
    CARD_SUCCESS
} from "../../../redux/types";
import Basket from "../../../components/Basket";
import {address, addToCart, card, removeASCart} from "../../../redux/actions";
import './style.css'
import AddressDialog from "../../../components/Address"
import Chip from "@material-ui/core/Chip";
import Api from "../../../services/api";
import {ENV} from "../../../config/env";
import Loading from "../../../components/Loading";
import CardListMemo from "../../../components/Card/ListMemo";
import CardAddressMemo from "../../../components/Card/AddressMemo";

export const Card = () => {

    const instance = new Api();
    const dispatch = useDispatch();
    const history = useHistory();

    const AppState = useSelector(state => state);

    const [step, setStep] = useState(0);
    const [address_id, setAddressID] = useState(false);
    const [loading, setLoading] = useState(false);


    useEffect(() => {
        if (AppState.card.ready !== 'success' && AppState.card.ready !== 'invalid') {
            setLoading(true);
        } else {
            setLoading(false);
        }

    }, [AppState.card.ready]);


    useEffect(() => {

        setTimeout(() => {
            const element = document.querySelector('body');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 200);

    }, [step]);




    const switchStep = () => {
        switch (step) {
            case 0:
                return <CardListMemo data={AppState.card.data}/>;
                break;
            case 1:
                return (
                    <CardAddressMemo address_id={address_id} data={AppState.address.data} onChange={(id) => setAddressID(id)} />
                );
            case 2:
                return (
                    <React.Fragment>
                        <Loading/>
                    </React.Fragment>
                );
        }
    };


    const renderCard = () => {

        return <div style={{ margin: '20px 0'}}>
            {AppState.card.data.length > 0 ? <>
                <Stepper elevation={0} alternativeLabel activeStep={step} >
                    <Step key={0}>
                        <StepLabel>
                            <span>سبد خرید</span><br/>
                        </StepLabel>
                    </Step>
                    <Step key={1}>
                        <StepLabel>
                            <span>ورود اطلاعات</span><br/>
                        </StepLabel>
                    </Step>
                    <Step key={2}>
                        <StepLabel>
                            <span>فاکتور</span><br/>
                        </StepLabel>
                    </Step>
                    <Step key={3}>
                        <StepLabel>
                            <span>پرداخت</span><br/>
                        </StepLabel>
                    </Step>
                </Stepper>
                <div style={{ minHeight: '300px'}}>
                {switchStep()}
                </div>
                <div className={'stepper-btn'}>
                    {step > 0 && <Button startIcon={<ArrowForwardIcon />} onClick={() => setStep(step - 1)} variant={"contained"} color={"secondary"}>مرحله قبل</Button>}
                    {step < 2 && <Button disabled={loading} endIcon={<ArrowBackIcon />} onClick={() => {
                        if (step === 0) {
                            if (AppState.address.ready !== 'success' && AppState.auth.login) {
                                dispatch(address());
                            }
                            setStep(step + 1);
                        } else {

                            if ( ! address_id ) {
                                toast.error('آدرس را نتخاب نکرده اید.')
                                return;
                            }

                            setLoading(true);
                            new Api().post('/order', {address_id}).then((response) => {
                                if (typeof response !== "undefined") {
                                    if (response.status) {
                                        if (response.id) {
                                            history.push('/invoice/' + response.id);
                                            toast.success('فاکتور با موفقیت ایجاد گردید.');
                                        }
                                    } else {
                                        toast.error('خطایی رخ داده است.');
                                    }

                                }
                                setLoading(false);
                            }).then((err) => {
                                toast.error(err);
                            })
                        }
                    } } variant={"contained"} color={"primary"}>مرحله بعدی</Button>}
                </div>
            </> :  <Basket />}
        </div>


    };
    // @ts-ignore
    return (
            <Container>
                <Helmet>
                    <title>سبد خرید</title>
                </Helmet>
                {renderCard()}
                <AddressDialog />
                {loading && <Loading/>}
            </Container>
    );
};


export default Card;
