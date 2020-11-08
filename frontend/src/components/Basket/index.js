import React, { memo } from 'react';
import { Helmet } from 'react-helmet';
import { Container } from '@material-ui/core';
import Grid from '@material-ui/core/Grid';
import Button from '@material-ui/core/Button';
import { Link } from 'react-router-dom';

import './style.css';
import {ENV} from "../../config/env";


const Basket = memo(() => {

  return (
    <>
      <Helmet title="Oops" />
      <Container>
        <Grid item xs={12}>
          <div className="NotFound">
            <h1>سبد خرید شما خالی است</h1>
            <h2>No Item!</h2>
            <img style={{ width: '300px', height: '300px'}} src={(`${ENV.API['FETCH']}/static/img/basket.png`)} />
            <Link to="/shop">
              صفحه فروشگاه
            </Link>
          </div>
        </Grid>
      </Container>
    </>
  );
});

export default Basket;