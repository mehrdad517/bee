import React, {memo, useEffect} from 'react';
import {connect, useDispatch, useSelector} from 'react-redux';
import ExpandLessIcon from '@material-ui/icons/ExpandLess';
import ExpandMoreIcon from '@material-ui/icons/ExpandMore';
import { Link } from 'react-router-dom';
import './style.css';
import {
  MENU_CHANGE_FOOTER_EXPANDED,
} from '../../redux/types';

export const FooterMenu = () => {

  const AppState = useSelector(state => state);

  const dispatch = useDispatch();

  useEffect(() => {
    let expanded = [];
    AppState.menu.data.footer_menu && AppState.menu.data.footer_menu.map((item) => {
      expanded.push(item.id);
    });

    dispatch({
      type: MENU_CHANGE_FOOTER_EXPANDED,
      payload: expanded
    });

  }, [AppState.menu.data.footer_menu]);

  const handleExpand = (id) => {

    let expanded = [];

    let array_index = -1;

    expanded = AppState.menu.footerExpanded;

    array_index = AppState.menu.footerExpanded.indexOf(id);
    if (array_index > -1) {

      AppState.menu.footerExpanded.splice(array_index, 1);
    } else {
      expanded.push(id);
    }

    dispatch({
      type: MENU_CHANGE_FOOTER_EXPANDED,
      payload: expanded
    });
  }


  const renderTree = (nodes) => {
    // nodes mapping
    const treeNodes = nodes.map((item, key) => {
      // id id node
      const { id } = item;

      // title title node
      const { title } = item;

      // slug
      const slug = item.slug;

      // external link
      const external_link =
        item.external_link !== null ? item.external_link : '';

      // check has child
      const hasChild = item.children.length > 0;
      // check has child is true fetch children
      const children = hasChild ? renderTree(item.children) : '';

      return (
        <li key={key} className="tree-box">
          <span
            className={
              hasChild === true
                ? 'tree-parent has-child'
                : 'tree-parent  has-no-child'
            }
          >
            <span onClick={() => handleExpand(id)}>
              {hasChild === true &&
              (AppState.menu.footerExpanded &&
              AppState.menu.footerExpanded.includes(id) ? (
                <ExpandLessIcon fontSize="small" />
              ) : (
                <ExpandMoreIcon fontSize="small" />
              ))}
            </span>
            {hasChild === true ? <span>{title}</span> : external_link !== '' ? (
              <a target="_blank" href={external_link}>
                {title}
              </a>
            ) : (
              <Link to={`/page/${slug}`}>{title}</Link>
            )}
          </span>
          {hasChild === true && (
            <ul
              className="tree-children"
              style={{
                display:
                  AppState.menu.footerExpanded &&
                  AppState.menu.footerExpanded.includes(id)
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

  return <ul className="footer-menu">{AppState.menu.data.footer_menu && renderTree(AppState.menu.data.footer_menu)}</ul>;
};


export default memo(FooterMenu)
