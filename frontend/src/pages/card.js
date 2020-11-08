import React, {useEffect, memo, useState} from 'react';
import {connect, useDispatch, useSelector} from 'react-redux';
import {toast} from "react-toastify";
// @ts-ignore
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
import Paper from "@material-ui/core/Paper";
import PersonPinCircleOutlinedIcon from '@material-ui/icons/PersonPinCircleOutlined';
import MobileFriendlyIcon from '@material-ui/icons/MobileFriendly';
import MarkunreadMailboxOutlinedIcon from '@material-ui/icons/MarkunreadMailboxOutlined';
import Typography from '@material-ui/core/Typography'
import ExpansionPanel from '@material-ui/core/ExpansionPanel';
import ExpansionPanelDetails from '@material-ui/core/ExpansionPanelDetails';
import ExpansionPanelSummary from '@material-ui/core/ExpansionPanelSummary';
import ExpansionPanelActions from '@material-ui/core/ExpansionPanelActions';
import ExpandMoreIcon from '@material-ui/icons/ExpandMore';
import Chip from '@material-ui/core/Chip';
import Divider from '@material-ui/core/Divider';
import Radio from '@material-ui/core/Radio';
import RadioGroup from '@material-ui/core/RadioGroup';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import FormControl from '@material-ui/core/FormControl';
import FormLabel from '@material-ui/core/FormLabel';
import Dialog from '@material-ui/core/Dialog';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import DialogContentText from '@material-ui/core/DialogContentText';
import DialogTitle from '@material-ui/core/DialogTitle';
import Api from "../services/api";
import {ADDRESS_REQUESTING, ADDRESS_SUCCESS, CARD_FAILURE, CARD_REQUESTING, CARD_SUCCESS} from "../redux/types";
import Master from "../components/Layouts/master";
import Basket from "../components/Basket";
import {address, card} from "../redux/actions";
import './style.css'
import isInt from "validator/es/lib/isInt";

export const Card = () => {

    const instance = new Api();
    const dispatch = useDispatch();
    const history = useHistory();

    const AppState = useSelector(state => state);

    const [step, setStep] = useState(0);
    const [open, setOpen] = useState(false);
    const [address_id, setAddressID] = useState(false);


    useEffect(() => {
        if (AppState.cardReducers.ready !== 'success') {
            dispatch(card());
        }
    }, [AppState.cardReducers.ready]);

    useEffect(() => {
        if (AppState.addressReducers.ready !== 'success') {
            dispatch(address());
        }
    }, [AppState.addressReducers.ready]);

    useEffect(() => {

        if (step === 2) {
            new Api().post('/order', {address_id}).then((response) => {
                if (typeof response !== "undefined") {
                    if (response.status) {
                        if (response.id) {
                            history.push('/invoice/' + response.id);
                        }
                    } else {
                        toast.error(response.msg);
                    }

                }
            }).then((err) => {
                toast.error(err);
            })
        }

        setTimeout(() => {
            const element = document.querySelector('body');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 200);

    }, [step]);


    const removeAsCard = (id) => {
        instance.delete('/card', id).then((resp) => {
            if (typeof resp != "undefined") {
                if (resp.status) {
                    toast.success('با موفقیت از سبد خرید حذف گردید.');
                    dispatch({ type: CARD_SUCCESS, payload: resp.card})
                } else {
                    dispatch({ type: CARD_FAILURE})
                    toast.error(resp.msg);
                }
            }
        }).catch((error) => {
            toast.error(error);
        })
    };

    const addToCard = (id, count) => {

        instance.post('/card', {id: id, count: count, is_product: false}).then((resp) => {
            if (typeof resp != "undefined") {
                if (resp.status) {
                    toast.success('تغییرات با موفقیت انجام شد.');
                    dispatch({ type: CARD_SUCCESS, payload: resp.card})
                } else {
                    dispatch({ type: CARD_FAILURE});
                    if (resp.msg === 'Request more inventory') {
                        toast.info('بیشتر از موجودی انبار');
                    }

                }
            }
        }).catch((error) => {
            toast.error(error);
        })
    };



    const switchStep = () => {
        switch (step) {
            case 0:
                let sum = 0;
                let count = 0;
                return(
                    <table className={'table'}>
                        <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>عکس</th>
                            <th>محصول</th>
                            <th>مبلغ</th>
                            <th>تعداد</th>
                            <th>مبلغ کل&nbsp;<span style={{ fontSize: '10px'}}>(تومان)</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        {AppState.cardReducers.data.map((card, index) => {
                            sum += card.off_price * card.count;
                            count += card.count;
                            return(
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>
                                        <Link to={`/product/${card.product_id}/${card.slug}`}>
                                            <img className={'card-product-img'} src={`http://api.digiattar.com/storage/product/${card.product_id}/50/${card.img}`}/>
                                        </Link>
                                    </td>
                                    <td><Link to={`/product/${card.product_id}/${card.slug}`}><b className={'p-title'}>{card.title}</b><br/><span className={'p-heading'}>{card.heading}</span></Link></td>
                                    <td>
                                        <CurrencyFormat
                                            value={card.off_price}
                                            displayType="text"
                                            thousandSeparator
                                        />
                                    </td>
                                    <td>
                                        <IconButton color={"default"} onClick={() => addToCard(card.id, card.count + 1)}>
                                            <AddCircleIcon />
                                        </IconButton>
                                        &nbsp;
                                        {card.count}
                                        &nbsp;
                                        <IconButton color={"default"} onClick={() => addToCard(card.id, card.count - 1)}>
                                            <RemoveCircleIcon />
                                        </IconButton>
                                    </td>
                                    <td>
                                        <CurrencyFormat
                                            value={card.off_price * card.count}
                                            displayType="text"
                                            thousandSeparator
                                        />
                                    </td>
                                    <td>
                                        <IconButton color={"secondary"} onClick={() => removeAsCard(card.id)}>
                                            <DeleteIcon />
                                        </IconButton>
                                    </td>
                                </tr>
                            )
                        })}
                        <tr>
                            <td colSpan={4}><b>جمع کل</b></td>
                            <td>{count}</td>
                            <td><b><CurrencyFormat
                                value={sum}
                                displayType="text"
                                thousandSeparator
                            /></b>
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                );
            case 1:
                return (
                    <Grid container={true} spacing={2}>
                        {AppState.addressReducers.data.map((adr, index) => {
                            if (index === 0 && !address_id) {
                                setAddressID(adr.id);
                            }
                            return (
                                <Grid key={index} xs={12} sm={4} item={true}>
                                    <div className={'address-box' + (parseInt(address_id) === parseInt(adr.id) ? ' address-box-selected' : '')}>
                                        <div className='address-box-header'>
                                            <FormControlLabel control={
                                                <Radio
                                                    value={adr.id}
                                                    checked={parseInt(address_id) === parseInt(adr.id)}
                                                    onChange={async (event) =>  await setAddressID(event.target.value) }
                                                />
                                            } label={parseInt(address_id) === parseInt(adr.id) ? <b>به این آدرس ارسال میشود</b> : 'به این آدرس ارسال شود' } />
                                        </div>
                                        <div className='address-box-inner'>
                                            <div className='address-box-inner-item'>
                                                <span className={'key'}>موقعیت:</span>&nbsp;<span className={'value'}>{adr.main}</span>
                                            </div>
                                            <div className='address-box-inner-item'>
                                                <span className={'key'}>آدرس:</span>&nbsp;<span className={'value'}>{adr.main}</span>
                                            </div>
                                            <div className='address-box-inner-item'>
                                                <span className={'key'}>کد پستی:</span>&nbsp;<span className={'value'}>{adr.postal_code}</span>
                                            </div>
                                            <div className='address-box-inner-item'>
                                                <span className={'key'}>گیرنده:</span>&nbsp;<span className={'value'}>{adr.reciver_name}</span>
                                            </div>
                                            <div className='address-box-inner-item'>
                                                <span className={'key'}>شماره تماس:</span>&nbsp;<span className={'value'}>{adr.reciver_mobile}</span>
                                            </div>
                                            <div className='address-box-inner-item'>
                                                <span className={'key'}>کد ملی:</span>&nbsp;<span className={'value'}>{adr.reciver_national_code}</span>
                                            </div>
                                        </div>
                                        <div className='address-box-footer'>
                                            <Button onClick={() => {
                                                new Api().addressDeleted(adr.id).then((resp) => {
                                                    if (typeof resp != "undefined") {
                                                        if (resp.status) {
                                                            dispatch({type: ADDRESS_SUCCESS, payload: resp.address});
                                                            if (parseInt(address_id) === parseInt(adr.id)) {
                                                                setAddressID(false);
                                                            }
                                                        }
                                                    }
                                                });
                                            }} variant="text" color="secondary">
                                                حذف
                                            </Button>
                                        </div>
                                    </div>
                                </Grid>
                            );
                        })
                        }
                        <Grid xs={12} sm={4} item={true}>
                            <div className='address-box address-box-dashed'>
                                <AddIcon />
                                <Button variant="text" color="primary" onClick={() => setOpen(true)}>
                                    ایجاد آدرس جدید
                                </Button>
                            </div>
                        </Grid>
                    </Grid>
                );
            case 2:
            case 3:
                return <div style={{ marginTop: '20px'}}>اطلاعات درگاه شما هنوز ثبت نشده است</div>;
        }
    };

    const AddAddress = () => {
        return (
            <Dialog
                fullWidth={true}
                onClose={() =>setOpen(false)}
                open={open}
                aria-labelledby="alert-dialog-title"
                aria-describedby="alert-dialog-description"
            >
                <DialogTitle id="alert-dialog-title">ایجاد آدرس جدید</DialogTitle>
                <DialogContent>
                    <form className={'add-address'}>
                        <fieldset className='add-address-fieldset'>
                            <legend className='add-address-legend'>جزئیات آدرس</legend>
                            <Grid container spacing={2}>
                                <Grid item xs={12}>
                                    <TextField
                                        label="نشانی پستی"
                                        variant="outlined"
                                        margin='dense'
                                        fullWidth
                                        name='name'
                                    />
                                </Grid>
                                <Grid item xs={12} sm={4}>
                                    <TextField
                                        type='password'
                                        label="کد پستی"
                                        variant="outlined"
                                        margin='dense'
                                        fullWidth
                                        name='password'
                                    />
                                </Grid>
                            </Grid>
                        </fieldset>
                        <fieldset className='add-address-fieldset'>
                            <legend className='add-address-legend'>اطلاعات گیرنده</legend>
                            <Grid container spacing={2}>
                                <Grid item xs={12} sm={12} >
                                    <TextField
                                        label="نام و نام خانوادگی"
                                        variant="outlined"
                                        margin='dense'
                                        fullWidth
                                        name='name'
                                        // value={AppState.auth.data.user.name}
                                    />
                                </Grid>
                                <Grid item xs={12} sm={6} >
                                    <TextField
                                        label="موبایل"
                                        variant="outlined"
                                        margin='dense'
                                        fullWidth
                                        name='mobile'
                                        // value={AppState.auth.data.user.mobile}
                                    />
                                </Grid>
                                <Grid item xs={12} sm={6} >
                                    <TextField
                                        type='password'
                                        label="کد ملی"
                                        variant="outlined"
                                        margin='dense'
                                        fullWidth
                                        name='password'
                                    />
                                </Grid>
                            </Grid>
                        </fieldset>
                    </form>
                </DialogContent>
                <DialogActions>
                    <Button onClick={() => setOpen(false)} color="secondary">
                        انصراف
                    </Button>
                    <Button  color="primary" autoFocus>
                        ایجاد آدرس
                    </Button>
                </DialogActions>
            </Dialog>
        );
    }

    const renderCard = () => {

        return <div style={{ margin: '20px 0'}}>
            {AppState.cardReducers.data.length > 0 ? <>
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
                {switchStep()}
                <div className={'stepper-btn'}>
                    {step > 0 && <Button startIcon={<ArrowForwardIcon />} onClick={() => setStep(step - 1)} variant={"contained"} color={"secondary"}>مرحله قبل</Button>}
                    {step < 3 && <Button endIcon={<ArrowBackIcon />} onClick={() => setStep(step + 1)} variant={"contained"} color={"primary"}>مرحله بعدی</Button>}
                </div>
            </> :  <Basket />}
        </div>


    };
    // @ts-ignore
    return (
            <Container>
                {renderCard()}
                {AddAddress()}
            </Container>
    );
};


export default Card;
