import React, {useEffect, useState} from "react";
import Api from "../../../services/api";
import './style.css'
import {toast} from "react-toastify";
const Invoice = (props) => {

    const { match } = props;

    const [invoice, setInvoice] = useState({});
    const [loading, setLoadig] = useState(true);

    useEffect(() => {

        new Api().get('/order/195').then((response) => {
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
        return(<div>sdfdsfsdf</div>) ;
    }

    return(
        <div className={'factor_container'}>
            <div className={'cart_factor'}>
                <div className={'factor_header'}>
                    <div className={'factor_header_center'}>
                        <span>دیجی عطار</span>
                        <span>فاکتور فروش</span>
                    </div>
                    <div className={'factor_header_left'}>
                        <img src={"/b_logo.png"}/>
                    </div>
                    <div className={'factor_header_right'}>
                        <div className={'factor_number'}>
                            <span> شماره فاکتور : </span>
                            {/*<span>{`D-A${routId}`}</span>*/}
                        </div>
                        <div className={'factor_time'}>
                            <span>  تاریخ فاکتور : </span>
                            {/*<span>{moment().format('jYYYY/jM/jD')}</span>*/}
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
                            {/*<span className={'member_information_box_value'}>*/}
                            {/*            {*/}
                            {/*                invoice && JSON.parse(invoice.address_regions).map((item) => {*/}
                            {/*                    return (*/}
                            {/*                        item.title + " - "*/}
                            {/*                    )*/}
                            {/*                })*/}
                            {/*            }*/}
                            {/*    {invoice && invoice.address_main}*/}
                            {/*        </span>*/}
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
                    <div>
                        <span>نوع تسویه حساب</span>
                        <span className={'points'}> : </span>
                        <span>پرداخت اینترنتی</span>
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
                                    <td>{item.price}</td>
                                    <td>{item.discount} % </td>
                                    <td>{item.off_price} </td>
                                    <td>{item.tax} </td>
                                    <td>{item.off_price * item.count}</td>
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
                        <th className="green">تخفیف</th>
                        <th>مالیات</th>
                        <th>قابل پرداخت</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{invoice && invoice.off_price}</td>
                            <td className="red">{invoice && invoice.post_cost}</td>
                            <td className="green">{invoice && invoice.discount}</td>
                            <td>{invoice && invoice.tax}</td>
                            <td>{invoice && invoice.total_pay}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );

}


export default Invoice;