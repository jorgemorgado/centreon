import React, { Component } from "react";
import routeMap from "../../route-maps";


class TableComponent extends Component {
  render() {
    
    //static elements ::
    //button install
    const stateInstall = (
     <a href="">Install</a>
    );
    //icon set when installed
    const stateInstalled = (
      <div className="buttons-set">
        <span className="info-installed">ok</span>
        <a className="upload-licence" href="">U</a>
        <a className="download-licence" href="">D</a>
        <span className="remove">-</span>
      </div>
    );
    //checkbox select all
    const checkboxALl = (
      <React.Fragment>
        <input className="checkbox" type="checkbox" id="checkall" name="selectall"
              value="selectall"/>
        <label className="input-control-label" for="checkall">All</label>
      </React.Fragment>
    );


    //configuration
    const { configuration, data } = this.props;
    //render
    return (
      <table className="green">
        <caption>Licence list</caption>
        <thead>
          <tr>
            {configuration ? configuration.map(
              ({ label }) => (
                  <th scope="col">{label === 'All' ? checkboxALl : label }</th>
              )
            ) : null}
          </tr>
        </thead>
        <tbody>
          {
            data ? data.map((row, index) => (
              <tr key={index}>
                {configuration ? configuration.map(
                  ({ key, type }) => {
                    let column = null;
                    let checkboxSwitcher =  <React.Fragment>
                                              <input className="checkbox" type="checkbox" id={'chec'+row.id} name="checkbox"
                                                    value="selectall"/>
                                              <label className="input-control-label" for={'chec'+row.id}></label>
                                            </React.Fragment>
                    switch (type) {
                      case 'boolcheckbox':
                        column = (<td>{checkboxSwitcher}</td>);
                        break;
                      case 'bool':
                        column = (<td>{row[key] ? stateInstalled : stateInstall }</td>);
                        break;
                      case 'text':
                        column = (<td>{row[key]}</td>);
                        break;
                      default:
                        column = (<td>{row[key]}</td>);
                    }
                    return column;
                  }
                )
                : null}
              </tr>
            )
            )
           : null}
        </tbody>
      </table>
    )
  }
}

export default TableComponent;