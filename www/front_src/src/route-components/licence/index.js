import React, { Component } from "react";


const tableBody = [{
    id: "1",
    checked: true,
    name: "Central Map Web Client",
    description: "Central Map Web Client lorem ipsum",
    version: "18.9.0",
    author: "Centreon Team",
    expiration: "17.05.2019",
    installed : true
  },
  {
    id: "2",
    checked: false,
    name: "Central Business Activity Monitoring",
    description: "Central Business Activity Monitoring lorem ipsum",
    version: "18.9.0",
    author: "Centreon",
    expiration: "17.05.2019",
    installed : false
  },
  {
    id: "3",
    checked: true,
    name: "Central Map Web Client",
    description: "Central Map Web Client lorem ipsum",
    version: "18.9.0",
    author: "Centreon Team",
    expiration: "17.05.2019",
    installed : true
  }];

  class TableRow extends React.Component {
    render() {
      const {
        data
      } = this.props;
      const row = data.map((data) =>
      <tr>
         <td key={data.checked}>{data.checked}</td>
         <td key={data.name}>{data.name}</td>
         <td key={data.description}>{data.description}</td>
         <td key={data.version}>{data.version}</td>
         <td key={data.author}>{data.author}</td>
         <td key={data.exporation}>{data.exporation}</td>
         <td key={data.installed}>{data.installed}</td>
      </tr>
      );
      return (
        <span>{row}</span>
      );
    }
  }
  
  class Table extends React.Component {
    constructor(props) {
      super(props);
    }
    render() {
      return (
        <table>
          <TableRow data={this.props.data} />
        </table>
      );
    }
  }

  //ReactDOM.render(<Table data={tableBody} />, document.getElementById("root"));


// const TableRow = ({row}) => (
//   <tr>
//     <td key={row.checked}>{row.checked}</td>
//     <td key={row.name}>{row.name}</td>
//     <td key={row.description}>{row.description}</td>
//     <td key={row.version}>{row.version}</td>
//     <td key={row.author}>{row.author}</td>
//     <td key={row.exporation}>{row.exporation}</td>
//     <td key={row.installed}>{row.installed}</td>
//   </tr>
// );

// const Table = ({data}) => (
//   <table>
//     <thead>
//       <tr>
//         <td>Checked</td>
//         <td>Name</td>
//         <td>Description</td>
//         <td>Version</td>
//         <td>Author</td>
//         <td>Exporation</td>
//         <td>Installed</td>
//       </tr>
//     </thead>
//     <tbody>
//       {data.map(row => {
//         <TableRow row={row} />
//       })}
//     </tbody>
//   </table>
// );
// class Tables extends Component {
//   render() {
//     return (
//       Table
//     );
//   }
// };

export default Table;
