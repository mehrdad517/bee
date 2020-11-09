import React, { memo } from 'react';
import { Helmet } from 'react-helmet';
import { Container } from '@material-ui/core';
import Grid from '@material-ui/core/Grid';
import Button from '@material-ui/core/Button';
import {Link, useHistory} from 'react-router-dom';

import './style.css';
import basketIcon from './../../assets/img/basket.png'


const Basket = memo(() => {

  const history = useHistory();

  return (
    <>
      <Helmet title="Oops" />
      <Container>
        <Grid item xs={12}>
          <div className="NotFound">
            <h1>سبد خرید شما خالی است</h1>
            <h2>No Item!</h2>
            <img style={{ width: '300px', height: '300px'}} src={basketIcon} />
            <Button onClick={() => history.push('/shop')} color={"primary"} variant={"outlined"}>  صفحه فروشگاه</Button>
          </div>
        </Grid>
      </Container>
    </>
  );
});

export default Basket;