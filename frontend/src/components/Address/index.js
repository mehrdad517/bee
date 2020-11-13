import React, {memo, useEffect, useState} from "react";
import Dialog from "@material-ui/core/Dialog";
import DialogTitle from "@material-ui/core/DialogTitle";
import DialogContent from "@material-ui/core/DialogContent";
import Grid from "@material-ui/core/Grid";
import TextField from "@material-ui/core/TextField";
import Autocomplete from "@material-ui/lab/Autocomplete";
import DialogActions from "@material-ui/core/DialogActions";
import Button from "@material-ui/core/Button";
import {useDispatch, useSelector} from "react-redux";
import {ADDRESS_DIALOG, ADDRESS_REQUESTING, ADDRESS_SUCCESS} from "../../redux/types";
import {region} from "../../redux/actions";
import Api from "../../services/api";
import {toast} from "react-toastify";

const AddressDialog = () => {

    const dispatch = useDispatch();
    const AppState = useSelector(state => state);

    const [options, setOptions] = useState([]);
    const [loading, setLoading] = useState(false);


    const initStat = {
        postal_code: '',
        reciver_name: AppState.auth.user.name,
        reciver_mobile: AppState.auth.user.mobile,
        reciver_national_code: '',
        region_id: '',
        main: '',
    }
    const [form, setForm] = useState(initStat);

    useEffect(() => {
        return () => {
            setForm(initStat)
            let n_options = options;
            n_options.splice(1);
            setOptions([...n_options]);
        };
    }, []);

    useEffect(() => {

        if (AppState.region.ready === 'success') {
            let n_options = [];
            n_options[0] = AppState.region.data;
            setOptions([...n_options]);
        }

    }, [AppState.region.ready]);

    useEffect(() => {

        if (AppState.address.dialog && AppState.region.ready !== 'success') {
            dispatch(region());
        }

    }, [AppState.address.dialog]);

    const handleChanger = (event, value, index) => {

        let n_options = options;
        n_options.splice(index + 1);
        setOptions([...n_options]);
        if (value) {
            if ( value.children !== undefined && value.children.length > 0) {
                n_options[index + 1] = value.children;
                setOptions([...n_options]);
            }

            let n_form = form;
            n_form['region_id'] = value.id;
            setForm({...n_form});
        }

    };

    const handleChangeElement = (event) => {
        let frm = {...form};
        frm[event.target.name] = event.target.value;
        setForm(frm);
    };

    const handleSubmit = (event) => {
        event.preventDefault();

        setLoading(true);

        new Api().post("/address", form)
            .then((res) => {
                if (typeof res !== "undefined") {
                    if (res.status) {
                        toast.success("آدرس شما با موفقیت ثبت گردید");
                        dispatch({ type: ADDRESS_SUCCESS, payload: res.address });
                        setTimeout(() => {
                            dispatch({type: ADDRESS_DIALOG, payload: false});
                        }, 200);
                    } else {
                        toast.error(res.message);
                    }
                    setLoading(false);
                }
            })
            .catch((err) => {
                toast.error(err);
            })
    }


    return(
        <Dialog
            fullWidth={true}
            onClose={() => dispatch({type: ADDRESS_DIALOG, payload: false})}
            open={AppState.address.dialog}
            aria-labelledby="alert-dialog-title"
            aria-describedby="alert-dialog-description"
        >
            <form onSubmit={handleSubmit} className={'add-address'}>
                <DialogTitle id="alert-dialog-title">ایجاد آدرس جدید</DialogTitle>
                <DialogContent>
                    <fieldset className='add-address-fieldset'>
                        <legend className='add-address-legend'>جزئیات آدرس</legend>
                        <Grid container spacing={2}>
                            <Grid item xs={12}>
                                <TextField
                                    autoComplete={"off"}
                                    label="نشانی پستی"
                                    variant="outlined"
                                    margin='dense'
                                    fullWidth
                                    name='main'
                                    onChange={handleChangeElement}
                                    InputLabelProps={{
                                        shrink: true,
                                    }}
                                />
                            </Grid>
                            <Grid item xs={12} sm={4}>
                                <TextField
                                    autoComplete={"off"}
                                    type='text'
                                    label="کد پستی"
                                    variant="outlined"
                                    margin='dense'
                                    fullWidth
                                    name='postal_code'
                                    onChange={handleChangeElement}
                                    InputLabelProps={{
                                        shrink: true,
                                    }}
                                />
                            </Grid>
                            {options.map((item , index) => {
                                return(
                                    <Grid item={true} xs={12} sm={4}>
                                        <Autocomplete
                                            autoComplete={'off'}
                                            onChange={((event, value) => handleChanger(event, value, index))}
                                            options={item}
                                            getOptionLabel={(option) => option.title}
                                            renderInput={(params) =>
                                                <TextField
                                                    autoComplete={"off"}
                                                    {...params}
                                                    fullWidth
                                                    name='region_id'
                                                    margin={"dense"}
                                                    label="موقعیت"
                                                    variant="outlined"
                                                    InputLabelProps={{
                                                        shrink: true,
                                                    }}
                                                />
                                            }
                                        />
                                    </Grid>
                                )
                            })}
                        </Grid>
                    </fieldset>
                    <fieldset className='add-address-fieldset'>
                        <legend className='add-address-legend'>اطلاعات گیرنده</legend>
                        <Grid container spacing={2}>
                            <Grid item xs={12} sm={12} >
                                <TextField
                                    autoComplete={"off"}
                                    label="نام و نام خانوادگی"
                                    variant="outlined"
                                    margin='dense'
                                    fullWidth
                                    name='reciver_name'
                                    value={form.reciver_name}
                                    onChange={handleChangeElement}
                                    InputLabelProps={{
                                        shrink: true,
                                    }}
                                />
                            </Grid>
                            <Grid item xs={12} sm={6} >
                                <TextField
                                    autoComplete={"off"}
                                    label="موبایل"
                                    variant="outlined"
                                    margin='dense'
                                    fullWidth
                                    name='reciver_mobile'
                                    value={form.reciver_mobile}
                                    onChange={handleChangeElement}
                                    InputLabelProps={{
                                        shrink: true,
                                    }}
                                />
                            </Grid>
                            <Grid item xs={12} sm={6} >
                                <TextField
                                    autoComplete={"off"}
                                    type='text'
                                    label="کد ملی"
                                    variant="outlined"
                                    margin='dense'
                                    fullWidth
                                    name='reciver_national_code'
                                    onChange={handleChangeElement}
                                    InputLabelProps={{
                                        shrink: true,
                                    }}
                                />
                            </Grid>
                        </Grid>
                    </fieldset>
                </DialogContent>
                <DialogActions>
                    <Button onClick={() => dispatch({ type: ADDRESS_DIALOG, payload: false})} color="secondary">
                        انصراف
                    </Button>
                    <Button disabled={loading} type={"submit"} color="primary">
                        ایجاد آدرس
                    </Button>
                </DialogActions>
            </form>
        </Dialog>
    );
}

export default memo(AddressDialog);