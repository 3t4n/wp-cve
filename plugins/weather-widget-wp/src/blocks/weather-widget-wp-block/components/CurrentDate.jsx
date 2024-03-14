const CurrentDate = () => {

    const dateObject = new Date();
    const day = dateObject.getDate();
    const month = dateObject.toLocaleString('default', { month: 'long' });
    const year = dateObject.getFullYear();
    const currentDate = `${day} ${month} ${year}`;

    return (
        <small className="current-date">{ currentDate }</small>
    )
}
export default CurrentDate;
