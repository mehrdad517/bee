import React, { memo } from "react";
import {Helmet} from "react-helmet";
import {ENV} from "../../config/env";

const HelmetProduct = (props) => {
    const { product } = props;
    return(
        <Helmet>
            <title>
                {product.meta_title ? product.meta_title : product.title}
            </title>
            <meta
                name="description"
                content={product.meta_description ? product.meta_description : product.short_content}
            />
            <meta name="canonical" content={`${ENV["MAIN_DOMAIN"]}/product/${product.id}/${product.slug}`} />
        </Helmet>
    );
}

export default memo(HelmetProduct);