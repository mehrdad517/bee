import React, {memo, useState} from "react";
import {useDispatch} from "react-redux";
import Grid from "@material-ui/core/Grid";
import FormControlLabel from "@material-ui/core/FormControlLabel";
import Radio from "@material-ui/core/Radio";
import Chip from "@material-ui/core/Chip";
import Button from "@material-ui/core/Button";
import Api from "../../services/api";
import {toast} from "react-toastify";
import {ADDRESS_DIALOG, ADDRESS_SUCCESS} from "../../redux/types";
import AddIcon from "@material-ui/icons/Add";

const CardAddressMemo = (props) => {

    const dispatch = useDispatch();
    const {data, onChange, address_id } = props;


    return(
        <Grid container={true} spacing={2}>
            {data.map((adr, index) => {
                if (index === 0 && !address_id) {
                    onChange(adr.id);
                }
                return (
                    <Grid key={index} xs={12} sm={4} item={true}>
                        <div className={'address-box' + (parseInt(address_id) === parseInt(adr.id) ? ' address-box-selected' : '')}>
                            <div className='address-box-header'>
                                <FormControlLabel control={
                                    <Radio
                                        value={adr.id}
                                        checked={parseInt(address_id) === parseInt(adr.id)}
                                        onChange={(event) =>  onChange(event.target.value)}
                                    />
                                } label={parseInt(address_id) === parseInt(adr.id) ? <b>به این آدرس ارسال میشود</b> : 'به این آدرس ارسال شود' } />
                            </div>
                            <div className='address-box-inner'>
                                <div className='address-box-inner-item'>
                                    <span className={'key'}>موقعیت:</span>&nbsp;<span className={'value'}>
                                                {adr.regions && JSON.parse(adr.regions).map((item) => {
                                                    return(
                                                        <Chip label={item.title} />
                                                    );
                                                })}
                                            </span>
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
                                    new Api().delete('/address/' + adr.id).then((resp) => {
                                        if (typeof resp != "undefined") {
                                            if (resp.status) {
                                                toast.success('با موفقیت حذف گردید')
                                                dispatch({type: ADDRESS_SUCCESS, payload: resp.address});
                                                if (parseInt(address_id) === parseInt(adr.id)) {
                                                    onChange(false);
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
                    <Button variant="text" color="primary" onClick={() => dispatch({type: ADDRESS_DIALOG, payload: true})}>
                        ایجاد آدرس جدید
                    </Button>
                </div>
            </Grid>
        </Grid>
    )
}

export default CardAddressMemo;