import React, {createRef, useEffect, useState} from "react";
import Api from "../../../services/api";
import './style.css'
import {toast} from "react-toastify";
import logoIcon from './../../../assets/img/logo-min.png'
import paymentIcon from './../../../assets/img/mohr.png'
import moment from "moment-jalaali";
import Container from "@material-ui/core/Container";
import Loading from "../../../components/Loading";
import CurrencyFormat from "react-currency-format";

const Invoice = (props) => {

    const { match } = props;


    const [invoice, setInvoice] = useState({});
    const [loading, setLoadig] = useState(true);

    useEffect(() => {
        new Api().get('/order/' + match.params.id).then((response) => {
            if (typeof response !== "undefined") {
                if (response.status) {
                    setInvoice(response.order);
                } else {
                    toast.error(response.msg);
                }

                setLoadig(false);
            }
        })
    }, []);

    if (loading) {
        return(
            <React.Fragment>
                <Loading />
            </React.Fragment>
        ) ;
    }


    return(
        <Container>
            <div className={'factor_container'}>
                <div className={'cart_factor'}>
                    <div className={'factor_header'}>
                        <div className={'factor_header_left'}>
                            <img src={logoIcon}/>
                        </div>
                        <div className={'factor_header_center'}>
                            <h1>بی نتورک</h1>
                            <span>فاکتور فروش</span>
                        </div>
                        <div className={'factor_header_right'}>
                            <div className={'factor_number'}>
                                <span> شماره فاکتور : </span>
                                <span>{`BN1` + invoice.order_id}</span>
                            </div>
                            <div className={'factor_time'}>
                                <span>  تاریخ فاکتور : </span>
                                <span>{moment(invoice.created_at, 'YYYY/MM/DD HH:mm').locale('fa').format('jYYYY/jMM/jDD HH:mm')}</span>
                            </div>
                        </div>
                    </div>
                    <div className={'member_information'}>
                        <div className={'member_information_badge'}>اطلاعات کاربر</div>
                        <div className={'member_information_box'}>
                            <div>
                                <span className={'member_information_box_title'}> نام و نام خانوادگی</span>
                                <span className={'points'}> : </span>
                                <span
                                    className={'member_information_box_value'}> {invoice && invoice.reciver_name}  </span>
                            </div>
                            <div>
                                <span className={'member_information_box_title'}> شماره شناسنامه</span>
                                <span className={'points'}> : </span>
                                <span
                                    className={'member_information_box_value'}> {invoice && invoice.national_code}</span>
                            </div>
                            <div>
                                <span className={'member_information_box_title'}> شماره تماس</span>
                                <span className={'points'}> : </span>
                                <span
                                    className={'member_information_box_value'}> {invoice && invoice.reciver_mobile}  </span>
                            </div>
                            <div>
                                <span className={'member_information_box_title'}>آدرس</span>
                                <span className={'points'}> : </span>
                                <span className={'member_information_box_value'}>
                                        {
                                            invoice && JSON.parse(invoice.address_regions).map((item) => {
                                                return (
                                                    item.title + " - "
                                                )
                                            })
                                        }
                                    {invoice && invoice.address_main}
                                    </span>
                            </div>
                            <div>
                                <span className={'member_information_box_title'}>کد پستی</span>
                                <span className={'points'}> : </span>
                                <span
                                    className={'member_information_box_value'}> {invoice && invoice.postal_code}  </span>
                            </div>
                        </div>

                    </div>
                    <div className={'condition'}>
                        <div>
                            <span>وضعیت پرداخت</span>
                            <span className={'points'}> : </span>
                            {
                                invoice && invoice.status === 0 ? <span className="red">پرداخت نشده</span> :
                                    <span className="green">پرداخت شده</span>
                            }

                        </div>
                    </div>
                    <div className={'factor_table'}>
                        <table cellSpacing="0">
                            <thead>
                            <th>ردیف</th>
                            <th>محصول</th>
                            <th>تعداد</th>
                            <th>قیمت</th>
                            <th>تخفیف</th>
                            <th>قیمت با اعمال تخفیف </th>
                            <th>مالیات</th>
                            <th>قیمت کل</th>
                            </thead>
                            <tbody>
                            {invoice && JSON.parse(invoice.basket).map((item,index) => {
                                return (
                                    <tr>
                                        <td>{index + 1}</td>
                                        <td>{item.title}</td>
                                        <td>{item.count}</td>
                                        <td><CurrencyFormat value={item.price} displayType="text" thousandSeparator /></td>
                                        <td><CurrencyFormat value={item.discount} displayType="text" thousandSeparator />&nbsp;%</td>
                                        <td><CurrencyFormat value={item.off_price} displayType="text" thousandSeparator /></td>
                                        <td><CurrencyFormat value={item.tax} displayType="text" thousandSeparator /></td>
                                        <td><CurrencyFormat value={item.off_price * item.count} displayType="text" thousandSeparator /></td>
                                    </tr>
                                )
                            })
                            }
                            </tbody>
                        </table>
                    </div>
                    <div className={'mini_table'}>
                        <table>
                            <thead>
                            <th>قیمت کل</th>
                            <th className="red">هزینه ارسال</th>
                            <th className="green">تخفیف (کد تخفیف)</th>
                            <th>مالیات</th>
                            <th>قابل پرداخت</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td><CurrencyFormat value={invoice.off_price} displayType="text" thousandSeparator /></td>
                                <td className="red"><CurrencyFormat value={invoice.post_cost} displayType="text" thousandSeparator /></td>
                                <td className="green"><CurrencyFormat value={invoice.discount} displayType="text" thousandSeparator /></td>
                                <td><CurrencyFormat value={invoice.tax} displayType="text" thousandSeparator /></td>
                                <td><CurrencyFormat value={invoice.total_pay} displayType="text" thousandSeparator /></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </Container>

    );

}


export default Invoice;