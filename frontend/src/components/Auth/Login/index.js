import React, {memo, useEffect, useState} from "react";
import {connect, useDispatch, useSelector} from "react-redux";
import Container from "@material-ui/core/Container";
import Avatar from "@material-ui/core/Avatar";
import LockOutlinedIcon from "@material-ui/icons/LockOutlined";
import Typography from "@material-ui/core/Typography";
import TextField from "@material-ui/core/TextField";
import Button from "@material-ui/core/Button";
import Grid from "@material-ui/core/Grid";
import {Link, useHistory} from "react-router-dom";
import Box from "@material-ui/core/Box";
import {toast} from "react-toastify";
import Api from "../../../services/api";
import './style.css';
import Dialog from '@material-ui/core/Dialog';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import DialogContentText from '@material-ui/core/DialogContentText';
import DialogTitle from '@material-ui/core/DialogTitle';
import {useTheme} from "@material-ui/core";
import Captcha from "../../Captcha";
import {AUTH_DIALOG, AUTH_LOGIN} from "../../../redux/types";

const Login = memo((props) => {

    const dispatch = useDispatch();
    const history = useHistory();
    const { dialog }  = useSelector(state => state.auth);

    const initStat = {
        username: '',
        password: '',
        captcha: '' // set for captcha text input
    }


    const [form, setForm] = useState(initStat);
    const [loading, setLoading] = useState(false);
    // captcha prefix
    const [captcha, setCaptcha] = useState('');
    const [regenerate, setRegenerate] = useState(false);


    const handleChangeElement = (event) => {
        let frm = {...form};
        frm[event.target.name] = event.target.value;
        setForm(frm);
    };



    const handleSubmit = (event) => {
        event.preventDefault();

        if (form.captcha !== captcha.join('')) {
            toast.error('کد امنیتی را به درستی وارد نکرده اید.');
            setRegenerate(!regenerate);
            return;
        }

        setLoading(true);

        new Api().post('/login', form).then((response) => {
            if (typeof response !== "undefined") {
                if (response.status) {
                    setForm(initStat);
                    dispatch({type: AUTH_LOGIN, payload: {user: response.user, token: response.token}});
                } else {
                    toast.error(response.msg);
                }
            }
            setRegenerate(!regenerate);
            setLoading(false);
        })


    }


    return (
        <Dialog
            open={dialog}
            onClose={() => dispatch({type: AUTH_DIALOG, payload: false})}
            aria-labelledby="alert-dialog-title"
            aria-describedby="alert-dialog-description"
        >
            <DialogTitle id="alert-dialog-title"><Typography align={"center"} variant={"h5"}>ورود به حساب کاربری</Typography></DialogTitle>
            <DialogContent>
                <Box mt={2}>
                    <form noValidate={true} onSubmit={handleSubmit}>
                        <TextField
                            variant="outlined"
                            margin="normal"
                            required
                            fullWidth
                            id="email"
                            label="نام کاربری"
                            name="username"
                            autoComplete="email"
                            autoFocus
                            onChange={handleChangeElement}
                        />
                        <TextField
                            variant="outlined"
                            margin="normal"
                            required
                            fullWidth
                            name="password"
                            label="رمز عبور"
                            type="password"
                            id="password"
                            autoComplete="current-password"
                            onChange={handleChangeElement}
                        />
                        <TextField
                            variant="outlined"
                            margin="normal"
                            required
                            fullWidth
                            name="captcha"
                            label="کد امنیتی"
                            autoComplete="current-password"
                            onChange={handleChangeElement}
                        />
                        <Captcha onLoad={(value) => setCaptcha(value)} regenerate={regenerate} />
                        <Box mt={2} mb={2}>
                            <Button
                                disabled={loading}
                                type="submit"
                                fullWidth
                                variant="contained"
                                color="primary"
                                size={"large"}
                            >
                                ورود
                            </Button>
                        </Box>
                    </form>
                </Box>
                <Box mb={3}>
                    <Button
                        disabled={loading}
                        type="submit"
                        fullWidth
                        variant="contained"
                        color={"secondary"}
                        size={"large"}
                    >
                        ثبت نام بازاریاب
                    </Button>
                </Box>
                <Box mb={2}>
                    <Typography variant={"button"} component={Link} to='/password/reset'>
                        رمز عبور خود را فراموش کرده اید؟ کلیک کنید
                    </Typography>
                </Box>
                <Box mt={2} component={"div"} style={{ position: 'relative'}}>
                    {/*{loading && <Loading />}*/}
                </Box>
            </DialogContent>
        </Dialog>
    );

})


export default Login;