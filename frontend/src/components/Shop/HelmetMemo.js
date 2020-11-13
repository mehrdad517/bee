import React, { memo } from "react";
import {Helmet} from "react-helmet";
import {ENV} from "../../config/env";

const HelmetShop = () => {
    return(
        <Helmet>
            <title>بی نتورک | فروشگاه</title>
            <meta name="description" content={'محصولات بازاریابی شبکه ای بی نتورک'} />
            <meta name="canonical" content={ENV["MAIN_DOMAIN"] + '/shop'} />
        </Helmet>
    );
}

export default memo(HelmetShop);