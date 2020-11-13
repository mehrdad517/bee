import React, { memo } from "react";
import {Link} from "react-router-dom";
import {ENV} from "../../config/env";
import CurrencyFormat from "react-currency-format";
import IconButton from "@material-ui/core/IconButton";
import {addToCart, removeASCart} from "../../redux/actions";
import AddCircleIcon from "@material-ui/icons/AddCircle";
import RemoveCircleIcon from "@material-ui/icons/RemoveCircle";
import DeleteIcon from "@material-ui/icons/Delete";
import {useDispatch} from "react-redux";

const CardListMemo = (props) => {

    const dispatch = useDispatch();
    const {data} = props;

    let sum = 0;
    let count = 0;

    return(
        <div className={'table-responsive'}>
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
                {data.map((card, index) => {
                    sum += card.off_price * card.count;
                    count += card.count;
                    return(
                        <tr key={index}>
                            <td>{index + 1}</td>
                            <td>
                                <Link to={`/product/${card.product_id}/${card.slug}`}>
                                    <img className={'card-product-img'} src={`${ENV['STORAGE']}/product/${card.product_id}/50/${card.img}`}/>
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
                                <IconButton color={"default"} onClick={() => dispatch(addToCart({id: card.product_id, count: card.count + 1}))}>
                                    <AddCircleIcon />
                                </IconButton>
                                &nbsp;
                                {card.count}
                                &nbsp;
                                <IconButton color={"default"} onClick={() => dispatch(addToCart({id: card.product_id, count: card.count - 1}))}>
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
                                <IconButton color={"default"} onClick={() => dispatch(removeASCart(card.product_id))}>
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
        </div>
    )
}

export default CardListMemo;