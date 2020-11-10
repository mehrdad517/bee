import React, {memo} from 'react';
import {connect, useDispatch, useSelector} from 'react-redux';
import './style.css';
import AddCircleOutlineOutlinedIcon from '@material-ui/icons/AddCircleOutlineOutlined';
import RemoveCircleOutlineOutlinedIcon from '@material-ui/icons/RemoveCircleOutlineOutlined';
import {Link, useLocation} from 'react-router-dom';
import Divider from '@material-ui/core/Divider';
import AppsIcon from '@material-ui/icons/Apps';
import ShopIcon from '@material-ui/icons/Shop';
import TocIcon from '@material-ui/icons/Toc';
import {
    MENU_CHANGE_BLOG_EXPANDED,
    MENU_CHANGE_CATEGORY_EXPANDED,
    MENU_CHANGE_MENU_EXPANDED, MENU_DRAWER
} from "../../../redux/types";
import logo from './../../../assets/img/logo_v.png'


export const SidebarMenu = () => {

    const dispatch = useDispatch();

    const location = useLocation();

    const MENU = useSelector(state => state.menu)

    const handleExpand = (id, type) => {

        let expanded = [];

        let array_index = -1;

        switch (type) {
            case 'category':
                expanded = MENU.categoryExpanded;

                array_index = MENU.categoryExpanded.indexOf(id);
                if (array_index > -1) {
                    MENU.categoryExpanded.splice(array_index, 1);
                } else {
                    expanded.push(id);
                }

                dispatch({
                    type: MENU_CHANGE_CATEGORY_EXPANDED,
                    payload: expanded
                });
                break;

            case 'blog':
                expanded = MENU.blogExpanded;

                array_index = MENU.blogExpanded.indexOf(id);

                if (array_index > -1) {
                    MENU.blogExpanded.splice(array_index, 1);
                } else {
                    expanded.push(id);
                }

                dispatch({
                    type: MENU_CHANGE_BLOG_EXPANDED,
                    payload: expanded
                });
                break;

            case 'menu':
                expanded = MENU.menuExpanded;


                array_index = MENU.menuExpanded.indexOf(id);
                if (array_index > -1) {

                    MENU.menuExpanded.splice(array_index, 1);
                } else {
                    expanded.push(id);
                }

                dispatch({
                    type: MENU_CHANGE_MENU_EXPANDED,
                    payload: expanded
                });
                break;
        }
    }

    const renderCategoryTree = (nodes) => {
        // nodes mapping
        const treeNodes = nodes.map((item, key) => {
            // id id node
            const { id } = item;
            // title title node
            const { title } = item;

            // slug
            const { slug } = item;

            // check has child
            const hasChild = item.children.length > 0;

            // check has child is true fetch children
            const children = hasChild ? renderCategoryTree(item.children) : '';

            return (
                <li key={key} className="tree-box">
          <span
              className={
                  hasChild === true
                      ? 'tree-parent has-child'
                      : 'tree-parent  has-no-child'
              }
          >
            <span onClick={() => handleExpand(id, 'category')}>
              {hasChild === true &&
              (MENU.categoryExpanded &&
              MENU.categoryExpanded.includes(id) ? (
                  <RemoveCircleOutlineOutlinedIcon
                      color="action"
                      fontSize="small"
                  />
              ) : (
                  <AddCircleOutlineOutlinedIcon
                      color="action"
                      fontSize="small"
                  />
              ))}
            </span>
            <Link onClick={() => setTimeout(() => {dispatch({type: MENU_DRAWER, payload: false})}, 100) } to={`/shop/category/${id}/${slug}`}>{title}</Link>
          </span>
                    {hasChild === true && (
                        <ul
                            className="tree-children"
                            style={{
                                display:
                                    MENU.categoryExpanded &&
                                    MENU.categoryExpanded.includes(id)
                                        ? 'block'
                                        : 'none'
                            }}
                        >
                            {children}
                        </ul>
                    )}
                </li>
            );
        });

        return treeNodes;
    }

    const renderMenuTree = (nodes) => {
        // nodes mapping
        const treeNodes = nodes.map((item, key) => {
            // id id node
            const { id } = item;
            // title title node
            const { title } = item;

            // slug
            const { slug } = item;

            const link = item.external_link;

            // check has child
            const hasChild = item.children.length > 0;

            // check has child is true fetch children
            const children = hasChild ? renderMenuTree(item.children) : '';

            return (
                <li key={key} className="tree-box">
          <span
              className={
                  hasChild === true
                      ? 'tree-parent has-child'
                      : 'tree-parent  has-no-child'
              }
          >
            <span onClick={() => handleExpand(id, 'menu')}>
              {hasChild === true &&
              (MENU.menuExpanded &&
              MENU.menuExpanded.includes(id) ? (
                  <RemoveCircleOutlineOutlinedIcon
                      color="action"
                      fontSize="small"
                  />
              ) : (
                  <AddCircleOutlineOutlinedIcon
                      color="action"
                      fontSize="small"
                  />
              ))}
            </span>
              {link ? (
                  <a target="_blank" href={link}>
                      {title}
                  </a>
              ) : (
                  <Link onClick={() => setTimeout(() => {dispatch({type: MENU_DRAWER, payload: false})}, 100) } to={`/page/${slug}`}>{title}</Link>
              )}
          </span>
                    {hasChild === true && (
                        <ul
                            className="tree-children"
                            style={{
                                display:
                                    MENU.menuExpanded &&
                                    MENU.menuExpanded.includes(id)
                                        ? 'block'
                                        : 'none'
                            }}
                        >
                            {children}
                        </ul>
                    )}
                </li>
            );
        });

        return treeNodes;
    }


    return (
        <div>
            <div className="sidebar-logo">
                <Link onClick={() => setTimeout(() => {dispatch({type: MENU_DRAWER, payload: false})}, 100) } to="/">
                    <img src={logo} />
                </Link>
            </div>
            <Divider />
            {MENU.ready === 'success' && <>
                <ul className="menu-container">
                    <li>
                        <AppsIcon />
                        &nbsp;
                        <Link onClick={() => setTimeout(() => {dispatch({type: MENU_DRAWER, payload: false})}, 100) } to="/">صفحه نخست</Link>
                    </li>
                    <li>
                        <ShopIcon />
                        &nbsp;
                        <Link onClick={() => setTimeout(() => {dispatch({type: MENU_DRAWER, payload: false})}, 100) } to="/shop">فروشگاه</Link>
                    </li>
                    <li>
                        <TocIcon />
                        &nbsp;
                        <Link onClick={() => setTimeout(() => {dispatch({type: MENU_DRAWER, payload: false})}, 100) } to="/blog">وبلاگ</Link>
                    </li>
                </ul>
            </>}
            <Divider />
            <>
                {MENU.data.menu.length > 0 && (
                    <>
                        <div className="menu-container">
                            {renderMenuTree(MENU.data.menu)}
                        </div>
                        <Divider />
                    </>
                )}
                {MENU.data.product_categories.length > 0 && (
                    <>
                        <div className="menu-container">
                            {renderCategoryTree(
                                MENU.data.product_categories
                            )}
                        </div>
                    </>
                )}
            </>
        </div>
    );
};


export default memo(SidebarMenu);
