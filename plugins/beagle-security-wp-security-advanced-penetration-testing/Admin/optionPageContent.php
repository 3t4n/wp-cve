<?php
function Beagle_WP_Page_Content()
{
	// define('__ROOT__', dirname(dirname(__FILE__)));
	require_once(sanitize_file_name('style.php'));
	require_once(sanitize_file_name('progressRound.php'));
	require_once(sanitize_file_name('script.php'));
	require_once(sanitize_file_name('gplLicense.php'));
	$beagleUrl = "https://beaglesecurity.com/";
	$beagleHelp = "https://help.beaglesecurity.com/article/30/where-to-find-access-token-and-application-token";
	$beagleDetReport = "https://beaglesecurity.com/dashboard/home";
	$beagleHeaderImage = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAxcAAAE7CAYAAACv0XxYAAAACXBIWXMAACxKAAAsSgF3enRNAAAgAElEQVR4nO3dT2wk2X3Y8deblROMk3AMzCZZJFhS2CDRCtiQu6Ag2NiIHCAQsFaA6YUNOH1ZcuADpYMxvZCDyAGs4cgIsEYsTI8dwOJBmKYuzMHCFg+2YF+mudBlY0JLQoGki7DsDZI9aBORBy+CKEEHv55fzdRwurteVb2qeq/q+wEaqzgcsrqq/7xf/f68zmQyMWimTi9aM8YkH2Lj0pMdG2POEo/R5KA74iUBAACArAguGkYDir4xZtMYs1zg2R0aYyJ5TA66520/rwAAAEhHcNEAGlBsG2O6BQOKefY1yIjafq4BAAAwH8FFoDq9aEWDiX5JAcUsF4lsBoEGAAAAnkBwEZBOL7qqGQp5rNZ85GMNNIaTg+5JUCcSAAAApSC48JwGFF193PD0aCXQGGqgcebB8QAAAKAGBBee6vSirmYofA0o5jnVQCMi0AAAAGgXgguPaEARP5Ya8JSOEoEGE6cAAAAajuCiZhVMeoqbsE/0cZbMKHR6kYysvaqjazdL7OU41CBjWNLvBwAAQM0ILmqgk576FQQUw6wb4lUwhYqJUwAAAA1FcFGRxKK9zElPTvejqDCrMmDiFAAAQPgILkqUmPQkC/SNkv5SJTtpJ3b+LqsfhNG2AAAAgSO4cKyi0bEykWlQV6N0BY3nTJwCAAAIEMGFI21ccFcYSMV7aDBxCgAAwGMEFwVQKvRYk0rAAAAAkA/BRUYVNTkPQ+49CLF5HQAAAMURXFhgPGt+Po/dBQAAgFsEF3Nomc92yXffW7WxXAVZHyZOAQAA1IjgIqGiBuWjRGN2a/sGKmiAHyfKy5g4BQAAUAGCi8cL3e0KJh4xWnUGzj8AAEAztDa44M65fyrKHDFxCgAAoCStCi6o+Q8HPS8AAADhaXxwUeG0IsailoRpXQAAAGFoZHDBPgvNxT4jAAAA/mpMcMEO0e3DDukAAAB+CTq4qKgB+DRxJ5uAwlMVNOgzcQoAACBFkMEFC0nMU2HAOSCDBQAA8KRgggtKYJAVpXIAAADV8jq4qKh5VxaGAwKKZqPJHwAAoHzeBReMHUXZKhxPLFmwERcUAAC0hRfBBRumoS5srAgAAOBObcFFRY23R4nGbOrhsVAFgwLGicljDAoAAACNU3lwoQu47QpGxzLpCbnxOgUAAMiukuCCO8IIVUUZNiZOAQCARigtuKCWHU1T0cQpeoMAAECwnAYXuviKG7OZ9ITGYqoZAADA0woHFxVNemL/AHirov1YhmTpAACA73IHF3rndtcYs1XSc6QOHcHp9KLNRKBRRn+RTEDbZf8MAADgo8zBhWYqpBTkdgnP5zRxh5aAAkEreZCBBBl9MhkAAMAnmYILvSs7dFz6wUhONFrJE6fuTA66u7yCAACAD6yDi04v2nWYrWDSE1opEWj0HfYoSYC+SbYPAADULTW40MXQwEFvRTz9ZkBAATgfbXuhAQbvLQAAUJuFwYUGFqMCCx/GaQIWNNDoF5w4RYABAABqlRZcnOQMLNgIDMgpMdp2O0cjOAEGAACozdzgotOLhhlLoS60fGpA7Tfghk6ckozGRoZfKO/FFd6HAACgajODi04vksXM3QzHcoegAihPjkltp5OD7hqXBAAAVOmp4EJLMt63PAaZUrNNCQZQvhzDFRhTCwAAKjUruLDts9jXTbzIVgAV6vQi6cW4b/kXXyH4BwAAVXkm+Xd00WIVWEwOutsEFkD1dFDCTcs/POASAQCAqjzKXGjJxZnFdJqjyUF3kysE1CtDBuP65KA74nIBAICyJTMXXYvAYqw/B6BmmsG4Z3EU9F0AAIBKJIMLmwUIpVCAX3Y16F9kQwc1AAAAlGoaXOiYy7QRl/uUVgB+0WB/2+Kg+lw6AABQtjhzYVPqRGkF4CEN+o9SjoxyRgAAUDrb4EKyFmdcDsBbaVOhljRDCQAAUJpnOr1oxaIkKuISAP6aHHQji94LggsAAFAqyVykNXpe6MIFgN/S3qcEFwAAoFQ2wQVN3EAY0t6rK1xHAABQJpvg4oQrAAQh7b2aVv4IAABQiAQXV1N+AZkLIAA2Qxd0J34AAIBSPMNpBRrlNOXJsJkeAAAoDcEF0CzsoA8AAGpDcAEAAADACYILAAAAAE4QXAAAAABwguACAAAAgBMEFwAAAACceJbTmF+nF63paM+1xIjPjRCfS42O9E+f6yZw8X9PJgddJh8BAAAEhOAio04v2jbGbBpjusaYpaAO3k/JYOxG8gg7vWismzhKsBHZbBIHAACA+hBcWOj0ohVjzC4BReWWjTFb+rirwUYkAcfkoBu17FwAAAB4j+BigU4v2tSgglInP0iwcUsenV50YYwZymNy0D1p+4kBAADwAcHFDJ1edNUYM9A75vDTUiLQOJXrNTnoDrlWAAAA9WFa1CWdXtQ3xpwRWARl1Rhzv9OLzrQnBgAAADUguFCSrej0Iqnjv0tfRbCWCTIAAADqQ3DxeKTsyeVpRQhWMsjY5DICAABUo/XBhQYWI12Qolnkmj6QjJT20QAAAKBErQ4uEoEFZVAe++W/XXjugGSkJIvRbfJ5AgAAqFtrgwsCi3Bc+3u/ZN69/S/NrddfNEtXPpX3uOU6v0MWAwAAoDytDC4ILMIy/vgT893/8j/M4M2Xzfm3v2Te+ernzY315/M+B8linOhrAAAAAA61LrjQu9ZDAouw3PveT83oRx9Pj7m7/ryJvvp588Eff9FsfeGFPM9DejFGTJQCAABwq42Zi6Hui4DAbP/pD8z5J794dNArz10xw6+8mjfIWNKJUru8DgAAANxoVXChDb2Mmw2UlEft/tlPnjr4OMh4/+3rZuOla1mf3O1OL2JnbwAAAAdaE1x0etGKZi0QsGR51GVry0tm9PXXzP0vv5q18XuLAAMAAKC4NmUu6LNoiMvlUZdtb7xgzv7ki1mzGAQYAAAABbUiuOj0or4xZsODQ4ED88qjkq5e+dQ0i3H3zZez/EECDAAAgAIaH1zoyNG7HhwKHFpUHpXUf/3FaS9GhjIpAgwAAICc2pC5YKHYUGnlUTHpxTh5+7pZXbauittiTC0AAEB2jQ4udMwoY2cbyqY8KiYTpaRMKkOAIWNqN9t+jgEAALJobHChC8PbHhwKSmRbHmUSfRgZAoxIp4wBAADAQiODi8Qu3GgB2/Iokz3AkB+KeA0BAADYaWrmQsqhlj04DlQgS3mUyR5grLKLNwAAgJ3GBRe6C/ctDw4FFcpSHmUSAcbytSs2P35bp44BAABggUYFF5RDtVuW8iijAUb0u5+3HVMb6esLAAAAczQtc8Eu3C2WtTzK6Jjagd1Ge8tabgcAAIA5GhNc6L4ENzw4FNQoa3mU2N54wdx6/UWbH72lZXcAAACYoRHBhY4LHXhwKPBA1vIosfubn7Ft8B5SHgUAADBbUzIXlEPhkTzlUdJ/MfzKqzY/ukRfDwAAwGzBBxedXtQ3xmx4cCjwSJ7yKOm/uP0bn7H50Rv6ugMAAEBC0MGFjge968GhwEMll0ftsns3AADAk0LPXFCegrnylEeJ6KtW42kpjwIAALgk2OBCd01e9eBQ4LE85VErz12ZZjAsbLB7NwAAwGNBBhedXrQpuyZ7cCgIQP87P8x8kP3XXzQ31p+3+VF27wYAAFDBBRfswo2sTscXucqjZHqU5e7djKcFAACtZwLNXOzqbsmAtTvf/Yk5GV9kOmEZxtOusns3AABAYMGF7o58y4NDQYBkelRW3fXns+zevcnrAgAAtFkwwQXlUCgqb3mUNHcvX7ti86MR5VEAAKDNQspcsAt3y8jUps3PXnv0cCFveVT0u5+3+VHG0wIAgFZ7NoQnr7sh3/DgUFAyCShkUlP3c8/PzBYcHn9kouOPzPDow9wHIuVRJ29fz/Rv4t27JThJIbt3b08OugQZAACgdbzPXOguyDTLtoAEFbLolx6HeWVIMh72/pdfNaOvvzYNRPIoUh5luXv3gN27AQBAG4VQFkU5VAvIVKa7b75sO/rVbLx0bRqIrNkt9p8y+N5PzdnPPsn87yzH01IeBQAAWsnr4EJ3P97w4FBQIskIbH3hhcx/QBb5ksG4ahmQJF188otc06MkmMmwe3c/8x8AAAAImLfBhe56zC7cDRf3MuQlAcZg6+Vc//roxx9PMxhZSfmWZE4s3GX3bgAA0CY+Zy4oK2kByyzAQpL1yNt/Ib0XecqjZHqU7e7deY4LAAAgRF4GF51eNNBdj9FgUs4kDdou9O02untK3vKoLLt36+sZAACg8bwLLnSXY3bhbgFXe1eItZX8Pf9SHiXjbbOS3bste0XYvRsAALSCV8EFu3C3S5GA4DLLHoi5JHtx/skvMv876ffIsHs3/RcAAKDRfMtcSPnIsgfHgZaR8qj+/g8zP+kM5VESSY0IMAAAQJN5E1x0elFXenM9OBQE6CJH1uGy/Xc/NKMffZz530l5l+XEqzjA6BY8VAAAAC95EVxQDtVOeRby85yML5z8nrzlURl275YfeqfTiyJ28QYAAE3jS+aCXbhb6OTMTUBgHAYq448/MYO/yL73hbHfvTt2wxjzQacXDclkAACApqg9uNBdjG805ozCmmQIpBTJheGRm98j7nz3J7kyIbIhoGX/RdKWZjLONZvRpy8DAACEqtbgQstCdnn1tJdsYleUBCh5NsJbJM/eF0bH097/cuYAw2jmToLsu8aY9wk2AABAiOrOXFAO1XISFLz1nexTmmKn44tcU55sfu/ge/nKo7Y3XsgbYCQRbAAAgOA8W9cBd3qRZCw2eMlAFvGy54XlhnSPSH9E3gZsG5JVkUzEynNW+1g8QQIM+Xfdb77nZJJVItiYlhB2epH850jaTeQxOeiOXPwRAACAImrJXOid19tcOcQkSLj5rR9YL8QPjz8ya//ugbMpUbNM974okFWREbUnb18vvMHfAhv6PnrQ6UWTTi+SAIMJVAAAoDZ1lUUxdhZPkabsld/5q2lD9emMoOFCG8Cv/8H3pxmBsjIWSRLERMcf5f73kr0Yff21aZmU5U7eRWywCSUAAKhTx/ybd0Yp5UnXXZZcdHqR7MJ9i6sOG3L332hvhuumbVsSFJz84fXpbtxFSQAl5VZS0lWTfTkMyqgAAEAZKg0uOr1oU0o4uJIIjezALRvluSL7ckhGJPrrj+oMNOjZAAAATlUWXOgu3CeUbSBU7799fbqXhWuSkZFgI34QbAAAgFBVGVxEbJaHkEljtvRPlC0ONqRZXf47q/+kIo+CDbkxMDnonvMCBgD4pLO+E++ZJsOCVuccmnyfRZPjvQEXr3yVBBedXtSVXYg9e+5AZtKYLWNmqySN68nMRo3Bxmki2BgRbAAA6tRZ31nT7yTbsoLTyfEee0WVrPTgQsuhztgsD02wdOVT5uxPvuikuTsvgg0AAKbBxVmOcvubk+M9ppaWqIpN9NiFG40h43Bl2tPgzZdre0oS2MjmfvIw9QYbq/qYTn/r9CKCjQU66zvbxhjJ4l716LDiG0fSD3c2Od47qfl4AMCKZi3y9PFusyVCuUoNLnQ6FH0WaJR73/vptDSqjObuPDwNNqS+dbOqP+y7zvpOWoa4Lk8cU2d9xzyVkTreI0gE4KO8N2ooiypZ2ZkLIkM0Un//h5U0d+fhUbCBhwv2XU8Di3mezEit7xxKI6Q2QxJoAAgd1TQlKy246PSibcbOoqmOfvxwn4p4Ae8zgo3adQM//hv6GHTWd+SG0WByvHfmwXEBADz0TBmHpE3cu1xwNJlkL0IUBxvSN3Ly9nXz829/yWx9odoJWC0zbzRiaJY0m/GBBBmd9R2fekcAAJ4oJbjQZhmyFmg02exOmrtDJ8GG7K0BZLAlDeCd9Z0+Jw0AkFRWcMEXDlph8L2fTsuMgBaSTMZdaVYniwEAiDkPLui1QJvIaNpQy6MARzY0i8EEFgBAKZkLshZolf13PzQnNEWj3SSL8b7u5QEAaDGnwUWnF601qHkRsBZ69uLqL9e34zga5T4BBgC0m+vMBVkLtJKMppWxrqHyZUNANMKAEikAaC/XwUXo89yB3PrfCTd70f2c//t1IBgSqUY0eQNAOznbRK/Ti7rseog2k83ohkcfmu2N8PaMkMzF6vISG+r55bSkbPCKPtb0UcYADvmdQ244AUD7uNyhmy8RtJ7sexFicCH6r79obn7rBx4cCdT55HhvVPbJ6KzvrOjn97bjnrkbnfWdzSqeAwDAHy7Loggu0HqysZ5kL0IkQdHGS9fafglbZ3K8dzY53htMjvcki3FdWogcnoPdtp9fAGgbJ8GFTomiJArQ7EWohl951SxdYXJUW0mWYXK8t2mMeUu2cXFwGjYke9H28woAbeIqc8GXB6BCzl6sPHfFjL7+GgFGy0kmQz/XXQQYTBEEgBYhuABKEHL2Qpq7JcBYvnbFg6NBXSbHeyeOAowbTI4CgPYguABKEHL2wmiAcfKH182t11/04GhQFw0wXPTT0ZMHAC1ROLjo9KIV+i2Ap4WcvRBXr3zKDN582Xzwx1+cBhlkMtpJpz3tF3zy3IACgJZwkblY4cUCPC307EVM+jAkyDj7ky+an3/7S+bB779m7r75sh8Hh6oUnfrE9wQAtISL4II7UsAcTQgukiSbsfnZa9OyKbSHjKstmL3Y4OUCAO3gIrigUQ+Y4+jHH5vRjz7m9KAJ2AwPAJDKRXCxxmkG5mta9kKcf/ILD44CFSsUXLDfBQC0g8sdugHMsP/uh+bsZ5806tScnKVOJz2p5khQFS2NAgBgoWcdnB5qaYEUkr3Y/c3PtOk0nXtwDIBTnfWdtXmlwDpVCzPoPifzqhxOJsd7fF4U0FnfWZkzNOFcx0mjgRZkg2u/7i6CCwApmhZcnIxdbNyMlglmAalf2pu6IJZF26rFv4n/56k+15Fm8EZtWTxrEBGft009d8sW/87oeTtLnDOCtUs0iIjPb/xYOF0j8bo80vM70vNLJjIAl95TeT+PKn9fEVwAFZCxtNLYLZOWmuD8b1J7LlgY4Ak+30HVRVtXH0Wz8fEX/6Pf01nfkS/4SB5Nu5OcOHfbNoueBVb1ccMYc7uzvnOh52yYd0GkmabtBVmTOAgc+hoAJp5D1yZQW2BDH1v6e8d6fgd5A43O+s52Ioic5azI9WsrfU/F17zIe8rMeF8ZDTTjz6NSgkyCC6Aikr1oSnBB5qJ99AuvUXRxtF1BeW/8BX9bA42BfrEHm9HQ7E5fFy1lWNKF8JYuhHcnx3vDDMcne7PctvhROf5deT4+BX762uw7WFzOI4HKLXl01neO9PxaBQF6Nz2yeN9s6PXbnxzvbZf0PBpBz2m35GseiwPNu3rth1neWzYILoCKRMcfNeJUy6Soi5RpUZODLneqmqfIZMAjX86Gfon3Nagocic4L1k43JcAo7O+M9A7x6GVjO1W3G8p1+m+BgypQYYeo01gEVvSgK/2iWYaVOxW/NqUa/lAF5p9iyAr6/WXAONscrxXdDPOxkl8HvXTStxKMg00bN9btpgWBVREFuRNCDAsJkWR1mimboFn5cUdYV24nenCs47AImlJj+Oss77Tr/lYUknmqrO+I3erH9Q4yCUOMkYpmbQ853NDS5BqIQGRLMA18KzrtSnX9X0NehfJk4Ugc3GJLujjz6O6d6aN31vyeVTks37KJrj4p0X/CICHor9uQHCRXhLFdJJmKvKFU2smSxaNnfWdE124+ba9/JKWJ5zUubhdRIOfkxJLoLKSRfDJgkVQ3hK+yjcFljvXuph/4EHAG7ulr8enzqO+RvO8h3x5brVLBJI+BBWXyXV6R24kaFYlF5vg4u9W9pSAhmtJ5oLgomH0jn/eL8GLyfFeVNcZ0buD71dQx1yUHN/IpyyGLnzl2t31NCh7Z8758v1aT+lCfaS9D75Z1QDucsBbeQDWFJ4GkvPc0KxqrptKlEUBFZLSqNCboclctIvevSpSK11LYKFf5KOMtfd1i7MYTpsr89C71iOPshXzeHG+stJF28jzQGhJA15KmgryPJCcJw7gM3/+2wQX/yjl/3+c9Y8CbSYjaUMlzdynBBdtMyh4l63yhZ9+kZ8EvMnrlpal1HKXOHH+gsgA6PkKJsDQxfo7HmaDZlnSWvzam91DpefO90BykdtZ318uggs2YgEyCDm4sCiJkklRBBcNoYugrQLP5qjqGfeJO4Sh13jHZVKVBhiJ8xfCwjdpK4Q77HqM9z04lKwiX3uCfKbX+0GA76fLMgXwlEUBFQs5uLDoGfFm5CiKcbQIqrR/IOCF8TyrVTbDayAT8vnz+g57wIGFiUuk6LmwF/j1nsU6wHAxLYp59shMNpNbee5KK0+c9F2c/ewTD44kO4vAiM+DBtCmw6Jfineq3JSsgYFFbLWKkp8GBBax2oYHLKKvz7QRr75bqqPMMUQNDCxiWzY9GDab6KVNiwp2h1FUb/c3P2P6r79olq58avq3xx9/Yrp/9F7rdnyW4CK04Mqy34LgImC6ABo6qA0+rXLDrBIXxhf6e0/i1/asMi/9+2t6V3dNx/a6rK+e9mBMjvfKXJwOSqoJv0icvxNdM5xL4Jk4b0bHx67po0ivjHfBUWJH6zKObazn9izx+Ssb1p1pU348TnYt8ShynZsWvDtXYiB5+fPofNYNnMT7Kn5PbTp+b9/Wz6O5gbxNcJF2QNRXw4oEFrd/4zNP/OjytStm9PXXzNrXHgR7Nz8PyQBI9iYkNnt0sDN3mPTLsF+wvyJ2WsNOx64Di315yduO0NUdtuPXvvybXf2C7+p5dfHFLlORRmVkgxz01sySeg4vnbfk8cTnrhvAtCobQ8c9QBf6O4eLXg8SYCT6Yh+dZw06urqxXahNxl4q4UaH1bVOmvW+SlzzvqPX4lB+p/6tp9gEF6bTi1YmB915jdsEF7AiGYtZJIsx/MqrZvMb32/NiZQsQGgs+i0Og3tSLVbSAkPuom7P+8Ipg5ZwuTj+C73bOHBx/Po7hvolvKnjfItOrxom7vQ7oa8Dl3dZJajY1YVtLpfO3YqeO9fBTyV05KyrAMnJa1SvzfT3OHxt4iFXGSrXn0fJa76t17xIkBGXyM3cB+NZy+aclXlToSYH3fNOLxqz+yIWkbv0cSnULBsvXZv+TMjNzlnYTF3yiQRDh+nBBVkLTyWaXDf183yzhM/sacai4sBi09Hc+MMygyItpdrUDd92Cyw+pP+i77g8auBoMXSq59DpDUddFG1rEDkIaRGsd7Fd9SiU8hpNvDa7eqyUPeWk728Xr897GqCX9XkUB+67BfcBuiGfwbNKRSW4+OcWv2AzZeEwCvWuAqpx/jfpd+ole7HyO3/FFfGQTUmUr42UAdvorO9MAjl8uVvdrziwcLFwu9DjrqRJVYIC3dgvKhDcScnV0MW51uDMxV31/cnxXqljYDVo2XSwIKpS39Fi/WbZr1EpX9Ms0bAhpWiVSmTYipDPo25V47ulLy7xeZT3dTpM9PU8ItOi/o7FP05Lw3LHEgtJw7Y0by8i/RfzSqdQr+HRh2l//3RB6SSaS7LWb8jCssrAQhWtHb7QTEul0290kbymd/rzWHI44tdF0/3NsgOLJB0UcFOvn7d0sVk0CJLn+EqFwa80CHf1ZgGyKZoBlM+Dlar3BYozVwXeT8uz9pex3ecirTmP4AKp+vs/TP0Zafq+uqB8qimOfhxO+Zc02lscL+MJ20W+iO7IItm26dklBwu3OLCopWdQA7FugS/0ftHN9TRrUbSEo/Q76rPo3yyyIKqCi7vYtbxGNVi8U/XfDZWDDGDlJaVJcVawwPvpqde6bXCx1OlFc7MXesdynPOg0BLSEJy2SJW+jMHWy7wkPDL43k9tDoaSqHY41bvGcoettJpgC0UWbrUGFjHtJZjZDGlhSRvxiyi6+N2vI7CI6fXzckfuxLSrIpz3r2ShGSIyGHZcfB7Vuq2DvtbyZkSXtWfnkSw7dKdlL1hcIJVN9mLrCy+YteVm95Qtam73jUVJ1BElUY0nzaTXJ8d7kqlwUu+fl2YtivT49esOLGJakpD3DnHu0ig9h0WyFqdVlkLNo1kzH++wbxcskblXR0bwMr3GR3Ufh88cZABrDyxierMg79THJz4PXAYXlEUglfRe7L+bulhtfPYilOBJAouL9LG5vPebT1L+DzrrO+ed9R1p/OzrArUORRa1h3XebZ9jkDPzv6z7k+RRtGfDm4yB3mH3bQFc5PxK4Oaqp8aFIuV7bVDkvXDHlxsdCf2c1/tGslQzS3Bxo9OL5tZ4Tg66J5RGwYZkL9IWrDKatrv+POezZrt/9pO0A7iYHHQJLtpjSQONu8aYD2SXVg00CtX/Z5T3y/zCxzIavWuZt6wi7/MpUrLj44LIm+uqAV+RQQM+BRZFX5+Npp97ebOoYw2MvZLYDyOPR58rWYILQ/YCLsieCRaLVnovaiY9MmkTvnjPt96qBhpnMiK07CBD63rzLtycbEZVBs2m5Lk5lzlIKLj4vXC84Z4TuiDypTyqSOB2VPW0IBu6rwo3j59WJKj1sl9IVR5cpL1pWGjAijQJ24ymlelRTSQbBvpu8BdWjdzeLTRQiyWd3nQ2ayyhQ3kXbl4uii/J8/25nKM8rcj18TZA0+vrQ/lOkeDC5wwB2Yun5b3Wpz4GkTF9j+dp5n80MStrcLGVUhp1VqAZBC2z/ac/SH3Csu/FynNXGndirv6y3w3dslO6xfjZfRq5cYkEGfe1L6OMLEZa9nwenxfFsbw357Kek7zn0Ph8A1Gvb61N0PqaX835z8eeLzjzZtcaSa913kbuEG7K5XovaYP7dIfurLopHzADdneEjXgBK/0V88hUJcle2AQiIfG9odvyfJOpxDzyHSD9GF1X9fkFy3lWdGdn313kmDK0mfG9mHfxe6TlRz4bFJwkVlSRwC2EBedAyyBR7FqH8nmUh3xOj/IEF/1FH2STg+6o04tOC3yAoUVkEfvBH39x4a8y/jIAAB+MSURBVBOW0bQytUiCkabwuSxKzrVFr4WMn2XzzHKdltTcGX8pypfASomf1RIIjOROlqMAo8iXeZ0LzrJZl0XFdxVz8n7cvLzOOus744IN1UXknd5lAhnnHxFcPFLkvVR053afTd8DeYKLVdlQT6dDzSPR7f0Gnzw4Irs/3/nuT8zt31jcWyHZi81vfL8Rp33V46yFNNv3v5O+Fwn1t5U4L6lM4qnfqYvOrj5cLsyWHAYYRRZuTZalNKPpi1+jx3mrpr+d9/yOA8gKTRvnaw7efMLn0WzT8yI9F3nqUBfeTdPRlKfujhVNJo3DNqNptzdeaMRZ8HnErs21IGvRPBLEyGz9yfGe3AV/RZv5XDXHSoAxdNCDUde+Gt7L0NSd9xpchLD4VXV+NuV9jYb0ecqGyQ8V2TivyaaZ8KwN3TFp7E57E3k1qxn+sr1bLtmLqwHtbD1P93N+BheywaFkkSyQtWgwyTDozrwrDsd7rjro0eHLfD7bRW3eUg7f9rVYpM5jzVtiGNJgjJBeC6WoeF+f4Mj5yRtcmLRxdnpnk23jYUXq/E/Hi2+Uymja/q+/GPQJlQZ1X5u5ZXNDC/tkLdpBpu/oJk+vOMpE39B9KuBe2YudYN7zAWVYkkL6TGVCICVRadYkuPhfOf9xf9FYWuXzJiHwjE32QnozQh5N62tpl+w7YjF69oKsRftoJmMt59zzy3JNxCnYiNwGtoudttxxrfzGpk4za4PWZy6QToKL/5bzPC2lLTR0Br4vu2bCczIN6vD4o9SDHLwZ7s7dPgYXUg5ls2P6dK8A9rVoLS2VKhpgLJe8yV5b2b4v85btsKBMlztw83l/i8sC2C+mCvR/pXimYIrrVlrvxeSgu0tzN2xJ9iKtofjG+vNB7HB9mUyJ8rEkSsYBWzRxj/W9jBZzFGDwOnKv7MUpC0rgMYKL+WT4w0iCi/9d8BfZpLm5UwUrMppWSnTShJi9kN3GfSPBXFqvi+I9jCkNMIrcMFpuUQlJFe4F2mdQJjItQD2mw5wkuPjPBf/8jU4vWlgPq3tiUB4FKzIONW0TN8kC+LhYn0cauX2bEhUdf2TuWQRy08ULTdx4UtFgk2C1OOkreENGCIf+REpA8ApUR+5QHhpjrk+O96ZTAfNsojfLMC1NJCUVGoQwThALyWha6QG4/+VXF/6cjKaVKVPn6SU9tZNAyKcxutJnIeVQFsaUseAy3Ql5v8DO192KxpUfTY73aAYvjjKQEsnoTnoZgpL3WrXm8+gZR+nD5U4vslmAdHWxAiwkQUPa9CLJBkiA4Ts5Tp9G6EowZtlnIbYnB12+9DBLrslPajnDxm9FMI/+SXmnKIUWXNRx3YuUpgWTaanofes7yu5SPONw4XC704sWvkH0b3Ud7vyKBrOZYHTr9Re9H03rW9ai+0fv2fZZUA6FuSR7UfBmUZZFSt4v87zTkfCk0IK0yq97wb6XkM4vwQVSxZvo/VdHp2qYtveF9l+wkRJSyWja/Xc/TP254VcWl0/VybeshWQsLPazEKeTgy613EhTJPi0Lg+gZMSZvOcxpDvrIfZbhHTM9LPkz1K15tzFwYVVV6eFVZv6bL0betPR30SDya7RaeU7Gy9d83Y0rU9ZCwksbII1zSxyAwA2qiwPyDWhig34npD3eoXUK1nnAi5v2VlIi87Wv58KZKmWpL/G8eF4KQ4uXH5ByN4XqZNAJgfdIQEG0kh/gM1oWh+zF8vXrniTtZAeFsvAwmifBaMtYaPId0fWBRV3C4vL/b4OKCNQ5+I3b2YopAV764MLlXccd2sauk0Jd58Gaf0XhgADlqT3Im00rSzkfWvuluPxIWshgcXNb1lNhhJvTQ66UblHBExlvYOX93uKxdBjRb7rQ8lm1nmcec/vUgjBmx6jfzvB1oPPowXi4ML1XUp58UVp/ReGAAOWpDwqjU8lSFKqtb3xQu3HkTGw2J8cdItMAALKlLe/40ZbShHSaBN+Xt4HF531nW7Ni98iPUgh7P1CH95jea91K0qOp8GFNlm7tiwnP0OAcZ0pUphHNnyzGU072PJj524fjkN6LDIEFkeTgy4bmyGrIndbM5WQTI73iizcWBQ9dpjz360GcHe91utc8DXq9aJTA3R68R7Le61lDHfjv2ufSfzvvI1Ii6zazkLXJu9N9sHAPDbZi60vvGDWluvN2sp43LqPIUPzttHaUb40kEeRxWaem1p5F8Z9shePNDJI0/0XfGg8z7uW8n3R2ack6jFt6s67Xm1VcFHW1I+tTi8a2vygZlDWSgp0EDjZVfqeRXN3nVmDuns/pAF+8xvfzxpYbLJRHnKqun44bz/QEtmLR4r0VG15vImaLyWdRc6vzWbEldPAnPfP0/Je642mZy+SwUWZm2VlCTDOJwdd+cK6V+LxIFDS3G0zmra7/nwtT1ACm7r6Ps5+9sk0sLDcx8IQWKAIHfG6XOBX5LmhFRUon+2zu/CjO655J90Yjxbxj+hr8YYnh1MkuPA1e7FL1mKmIu+FQZOzqVUFFyZLgGEeBhl9bfSmDwOPyJ15m52761jk31h/vragRnpS1r72wHbnbaPp3G0CCxRQ9E5m5uBCN9Mrkr2IKI+asv4unuGGT3uH6PUs8nyc0uCtSPWFV4tOvda3PDgU7xS81ks+vW5dexRc6CKjyN0MG9MAw6bJ2zxu9KYPA0+QfS9sRtNWuceENJPXtdeGBFtvfPO91IxOgrzP10oa5IAWcHCneFxgI6oipSPWfYANV3RR41OQNiyYQStDkfPrzaLTt8DNU0UDdS9L4Yp65tK/Lzt7IbZsp0gZ+jAwhzQsp5HRtCvPXankFEpgUXWmRMqgJFtx57vpmZwESqFQiKMFR+7vGgd3hqVvwOsFk5RvSQBX1gJeM0D7BX7FUsHyHyc66zsDj8qhHpkc7w0LVl3UvujU197Iw8DNK3qti9wAv+17/4VMicuarawjuDB69+jEZqM9Qx8GZhj96GNzePzRwlMj2YQqmqvrKIeS7E3GMihDYIGiHC44ii5Mi5ZkeRlgyCKjs74jwdMHxpgHxpify3GWFGQUXbxu1HkOO+s7fc/LdYpmyGpbdCbe56t1/P0AFX0v3fcxgyHHpJ9H78vnUWd9ZyIBvc3n0RPBhe7MW1WPQ7wPhvUITPowkNT/jt1o2s3PXivtvFVdDhU3bb/1nR9mKYMyukHeGoEF8nK44JCSqELBhW4GV+TOu9EA48SHJm/NVMi5vT8jcJNs/5nrPSY0A+TiHJYV/MylQc3dKv9mDgMHa5X7VQcYBBbZafaiaHWNBJNelBtq1lQ+Y2/P+Dy6NV27pxzn5cyFqTB7YTS1+k6nF1lHbPRhICYLbZuSoMGb5Y2mrbIcSnorJFuRYRpU7A4b5KEI3fn4zNGCw1XPQ9/B4u1hFv3hXfDKyRe03rH8IGWPhmkdfgkLDxd3S7d0sVF6kJYIwrbK/ltFaemZi9f6/aoCOC19cfU+bxsXnyE39POoln2n9P011KzpotfAalqAMSu4qKOO8nanF0X0YSCrwV/8NPUO/uryktneeMH5ua2qHEomQa38zl9NA6mM2Qr54TcmB91GNoyhfHoHSxZz7zgaRXnhqkFUF28ugmZ5Xnc1i1HJFKREUHGmdwdtrLr+ftbsxR0Hv6r0IE1/94knG+VZmRzv7Tq6Ebql57eU16e+HuNFJSNnc9Bsqov30vL0pvv6zqjCz6M4qPggQ+C+uujmRGcymTz5f3i4wP950YPN6VTHY1pPsen0ogFj0tpNAof7X15cmiTTpWSB7opMozr5w+ulZi2kr0SyFTkyFbHbk4PuN5wfGDKROtWcZ+xocrxX+chPLb/p6sLddTPnTS0hcEZKCRw39Z7qHedIAxiXx7qmdziL3Hl/o2hZ2aVjuqqLdlfXWhbTuy6usx5bVxcxVTQWX58c7zmt3tAF4gOHv/JIz2/h49Rs03ZVu29Pjvc6Ln9fkXPr+lhiWk7kMvMj13vo+nPTPM5Ibxf8/Pz0rMl/TwUX5uGC3fWHdRYXGmBYf3h2etG2fhkQcbfUydvXpxmKRW5+6wdmeGS9c/VCD37/tdJ6ORwEFbFfoceifgWCi3GFYyBX9FHmXeFSgqWSa8QPNVtwoncm8xzbpi6Qi246GLs3Od5zmiEoYQFs9PUb6cLI/obhk+esW/H3uvPgwjyeauX6Juipfj5EWcY6a0ARn99K13ktCS7k/J6U8Lq90PeTvD5HeUZ5J679psP31luT472nyv/mBRfb2lhWp3vawG1FJ09FjE1rJ1noy4J/EVmsSzN0Ubdef7GUPg4pf5IyLwdBhTicHHRrqdvEkwoEF00iX4wrrjMBMc0IjCpYiB5pKVPyi32kZbpxWe9V/X+vlPR9tD853nPeQ6VlWrYlWnkc6aIrfg2c6DmKz1t8zuqs9y8luDDl3NFOutDzmTz2kS4iY5slviattCG4MI8/j94v6/er+JonP4/O9f+WvO7xjaO1kj4f72j53xNmBhfm4WL93INMgHwYdW3vvmpJVxRSTSbckeZqmQ61yCtfe2BOso1vfYJkR0Zff81ZOZTsOC7ZFAkq0jYGzOiNLNk/lIfgYvoluJnnzn8WFQYYdZt5p9AFrbv2vlk6xbjAArrM4OKqLgJDf32e5g2S2hJcGB0r7cFN+irMLNOc1dAd82FhssF+GLAlpURpijZ2u5oOJVkK2QjwV377z6djZR0HFhcEFvBIv+zAwjxuqNxs+KhyZw3xc/R18RiqCy338I5m7UJ/fZ46mjDWeNojcbPhz3PuWPFFwUUpd0ZyiPfDsE4Dsx9GO8lo2rSSou7n8k93uvvmy2Ytpa9jkTiguPrbf27e+OZ7Zv9dN/0fM3i9+zBa46KMBu5FWhBg9MsqLTNPLoBDDTAqCWTzCvz1GQdu9PFZSgQYTf08mhvIzw0udGKTLx8wS9NZzw8nQ1lhP4x2kvKiRWTK08pzVzKfm42Xrpn+6y9m+jdSfiU7aXe/+d4TAUXGcbJ5+HJjAO0Vl0JVHujqAm4t8Dvws1QSqAUcYFQayOYVaIARv58zNxG3nb4mm3bD40JLCOcG8osyF8bDRcqtTi8asR8G5pHsQNriPeuUJ5tduCVrEk95ioMJ6e+QkqdDi2Oy9J7Fjx1NDrp8AaBOh9q8XdsdZF0EbTrYgdoHcoPslYozQHGAcej1mXksiMAilggwQrj5WUnPVJPpuVtpyFr0VF8PC3uT5jZ0P/qBXnTm4QSmsTZ6sx8GnhJ99fPTDe7mkeyBlCfZeuern3+iz0IyEud/84tH/3U03WmRsda5blo0W16fHHSr3GUfKVrU0H2hZSleLfJ0lvsw0Ebaw+lo9hJLodJUMEWqiJmvuQLvudIaumfRJm+fh9A8XGslAgufmqh9buieRzeD3A3088h6DHZa5sJ4Wr9NHwbmkgzCIlnLoqSc6foffP/RQ7IRslu2ZCRKDiyONFhY0Sk4aYHFmMACNbjQnWlXfLx7rA2HK4FlMcY6haVbZ2BhHu8yfd3Du+zjBaV3QZR0ybXVvV9c7OzsmgS2aw3NWNT2WtZJb2sBZQWNvp9eybK/jk1wMfB0QU4fBmZKGzUr/ROe25++kQ+6m4lgwWZCB1M8/NS02v/YOBFU7Na9CF5EF3FyM+rTnpcmXOjc+BWXu3AXpXfz1zxaBN9LWfjmXRDXUlKqAZwvr80LHXdce2BrIe/1qjVgkrJNOb8atPv8eTTWksPMQWZqWZR5WFLkc1rU5NwPo6wdXeGBycHiaYSdnneTWscayA8vv451FHPahjxjzXDAMw2bdx7vEhv5tPjNSvfE6Hu0p0O8G/vA9wWd7vK7W9O5k+/63dR673zlMqXsIJ+VHvtuTaVS+3p+5y7afStFyrk3S6Xlb2n0nPar3jF9gbG+DnJnom2DixA2f8nTh9GEDYMww/m3vzRtxJ6n6GZ6jox1oTZc9LqVIQYWXzQ3NTMHD3XWd0Lt+Rondv4dNa1EQmvet/VRx82mw+n7P8BATYOMvp67KnZGTw0qkjSoH1geW9yk6k1gV/GCMzWouHRcPgUXWftWvG3+1/dU/HlUda/zxaP1iIPAyyq4MGFkL8yj5q4Miyzt22jDLoqtIrtoLyp/kt6JtN6MkpzqQm1hQBHr9CKbD3KyFgHQu+VebvB1yfSLxac7e1XQL/auls5ulrRgHseBmmZ/GrFngDbNxw9X5+1UszlR3hGoiWu6aMLkic/BnS6e43PrMtA4TGQhrV+HvjZR63EtyjydF3ktVU2/Lzb1upeVxTpN3Dhy+h7IElyEtHX9PW3gtqJlJ6MGbMsP5VFwkVxMjLKOibWc1kbWAnBMF6ZricdV/d823xNj/b4818zPiS5iGz8mWhdFyceKxWfYqZ6rUZvOVR6JRfSKPmxek0eJ1+KoyI2DECc0NUHifbWSCKKyfh7Fj5G+x0q7uWEdXJhwshcx+jBarKbgYpx8407fvAX2nLB8v5G1AABUoshoYoKL9ng24zMd1FQLloekkU46vciqD0ODkDX6MDDHODGZ4jwxbeJhCYnjEbCdXhTXM6exHscMAEBBVpsYz8A2AC2SKXNhwuxRoA+jhSYH3XFKEHw9Dgx8ZNnELbtx1z7dBADQDp31HZvvplm8mMaFatjsc/EEXaSHtIV53v0wXiHSDtaRRXbN58Cib/nhTdYCAFAJbS7P21zciOEFsJM5uFDWzdIeuSV3g7W3IpWWUq00eAOsxvr1tX/430N9bloOZbMZ3p0i/RwAAGRUJPPQxJ2+MUeu4EIX3vcCPKkScZ/pdKhU0ocxOeiu6QxoBOLNL7zwP1OO1OfM29Bi+kO84R4AAFUpMkqb4KJF8mYujN5dHQd4qmTh9r72VViZHHTlZ2/We9iwdPFbv/qP/37Kj3p5xz9DOVTfdgoaAABFaUlUkWE3BBctkju40MVNiOVRsfs6GcoKfRjBiCzurnj3IafZtLsWP3o4OegGt5svACBoRdZ7Y/YtaZcimQuji5zDgM/YVqcXndCH0Rx/+turH1mUFXnVzK2vP5uA4YImbgBAlTRrUSS4aNVu/ygYXKjtwO/mr9KH0RjjL/+rTz+f8mTGHmYuhpZ7x2xTDgUAqJhNL+AiZNtbpnBwoYudIk0+PqAPowE2Xrr2HYuaUK8+5LTP4obFj+5TDgUAqFJnfWfb8jtqnovJ8R7fXS3jInMR704c4vSoy+jDCNfF9772q//U4uitr2/ZOr1o07LPYhx4fxMA4BIpN+qs73i7sVxnfWfNwWRCb75zUZ3MO3QvIv0LWmYUOump2LQtQdGa+VFDnnuQvvgv/sG//cvf+7X/mHLsY+2ZqZ2W4Y0sU82vaL8PACBw2sMwvJQRkFLrXV8anzWwsP2OWuTTNHO3j5PMRUK3IXfx6cMIy+lf/t6v/WuLI7bZnK50Goza1rC+RWABAI1yObAwWtL7QWd9Z1eDj9poNsVFYLFPYNFOToML3TG4KdNs6MMIxL/v/rP/ZLE/xIVH/Ra2WS4ZO8tmeQDQELpwX9TDcHt6c7OmIEP+rjHmgYPAwvhyQw/Vc525iMfTNqH/IkYfht/u/Yff+uw/sThCWaTXPmlJX0s2gcUpY2cBoHFs1hNLiSBj0FnfKb2cVxq3O+s7Z/p3XbhD1qK9nPZcJHV60chyt+FQ0IfhH+mhWNOM0aLGaC96LTSwsNnh9EJfa5RDAUBD6OSl+zmfzakGJqPJ8Z6T7wbNjGzrwBCbcei2Hn43H+8xOr2lygwurup+Ai5fsHXLvOjLsKBEdtd1UpkEDh8s+Ndv1F0SpeV1tl8qbzB2FgCaRTMDLtZE8X5NJ3oT88wmS6AZEHlsao9sWTc/X3EVACFMpQUXJvtEnJDc1PIn2/NQ5G4FZrs3Oegmx7P252Qv9usuL8p4/d+izwIAmkWzBD+v4Ekdzfi/rVR4o/etyfEe32EtV2pwYR4urCQ6fqeBp3lfy3GsNDjQqoNkkFZmlKh1NZCIm+CGdc/YzhhYZHpNAQDCoI3cDxp+uWQ6FN9hKD+4MM2+c08fRj2CKBvK+Lo/mhx0vd1MCQCQX4WZi7oQWOAR59OiZtESoiZNkIqxH0b1DhsYWJxq1gUA0EDa3DyrZKkJCCzwhEqCC/NwYd1v6KI63g+jb/GzU+yHkdtFCONZMwYW4yzZLwBAsLYbOKb+DoEFLqukLCqp4dOTJHjqZyiTog8jG+/LoTIGFoycBYAW0YlNwwaM6p/e7Jsc7zHZEE+pPLgw2ffA+L/GmGdLPiSXpiUuult5KvowrEk5lNelQwQWAAAbuufFINCbi4caWJBxx0yVlUVd0tVFuA0JLP5fQJdPgoSTTi+yas7VLMcmfRgLXeioWW9pWRyBBQAg1eR4b6gjYu8EVCo1nlYQHO91CSywSC2ZC5Pvjr0EGH+r5MNyLdOeBbpAXbTTdFt5vfdDxlI/AgsAwCM6SaqvDx8zGRJU7GpABKSqLbgw+QKM/2OM+aWSD8u1rH0Ym7qbNH0YD3k9opXAAgDgggYZXQ0yfCiVlvKnIX0VyKrW4MK0J4ORtQ9jRQOMtvdhyGJ8zfa8VSnH65bAAgBgRRu/u/qosvn7SNcf0eR4z7vvXoSh9uDCPF6oRQ2YnrDIhQYYI5sf1nMyaPBkLRtelkPlCP4ILAAAuekO3/JY08eyg7Mp300neqNs+l96KeCCF8FFrOFjamP0Ydg51Q0HvZJjfDCBBQDAuc76jnwfXdWHzffluQYR0lBudaMTyMOr4MK0J8CgDyPdK74tyDOOmjVZy+EAAABCV9co2rl09+p7Gf9ZSKNqjQZPIy2vSaWlVGsZxveG7o6HgcUgR2CxSWABAADaxLvMRSzHXWJ5Ip1yj8o5+jCe5lU5VM5+oCO9rtSuAgCAVvE2uDCPA4xQd7DMgj6Mh7zqT9D+iihj49y+Zt8AAABax+vgwuRroA0VfRjG3JwcdL3YpCdnYCvlXLslHhYAAIDXvA8uTLv2fWjzfhheLMxzlp5daGDI7qUAAKDVggguzONFnyzebmT4Z/RhhMGLUqKcwRqjZgEAAFQwwUVMp/bc8uNoStWWPgxfAos8ZVDxRCgatwEAQOuZEIML065G76b3YdybHHT7dR5AgcxPpmsDAADQBkEGFyb/JJ8Qyd3xbduym4D6MGpv3tbX0DDHucqUVQIAAGgL7zbRs6WLbVkcHoZxxLmt6oZ7XZtfoM3gmzk2IqzKWHffrjuwkIzJ+zn6K14hsAAAAJgt2MxFUqcXyZSh2/4cUWkyTVTSMqmhR9kdCXh26ywl0szOMOOmeIaN8QAAANI1Irgwzd33YZZDLZOy7cOQnoK+Puo6N0fan1DrRCXN/gxznAf2rwAAALDQmODCPF5IRznuSocmUx+GeXzHXgKM7QqDjCPNVFiN1S1LzjHGJutYYAAAgLZrVHARa0mZ1IUGGFGWf6QL7W19lNH0faEB3sCHvR8KZCsogwIAAMiokcGF8bPfoCy5S3Y0m9HVR5Fsj2RS5O7+KGuwU5YC2QpDGRQAAEA+jQ0uTPEFZkgy9WEsOF8yfWtFp3Bd1f9edqYP+VsnPpYMFchWjDVbwW7bAAAAOTQ6uIi1ZNO9zH0YTVMwmGRTPAAAgIJaEVyYYiNIQ5KrD6MJdN+K3RwBZGvPGQAAgGutCS5iLWn2bk3PQMGgkaZtAAAAh1oXXJjHvQXDkqYl+cJJH4bPCgSKFzoil522AQAAHGplcBFrQRajkX0YOglskDM4PNJzclbCoQEAALRaq4ML044sRmN6CrRhW4KKrRz/nGwFAABAyVofXMRakMUIug+j4MQvshUAAAAVILhI0CzGoMETpYLrwyh4TS50vOywhEMDAADAJQQXMxQYaxqCIPowtARKrsGtnL+i8Q3tAAAAviG4mKPh+2JcTA66Vz04jpkKlkCNNVvBvhUAAAAVI7hI0elFXQ0yGpXFmBx0Ox4cxhMclKXd06ZtshUAAAA1ILiw4KBExzs+BRcOzm8jR+4CAACEhuAig4L7K3jFl+CiYAkU42UBAAA8QnCRQxMavusOLjRQ2y1QAnWovRWMlwUAAPAEwUVO2vAtd8xvhHj8dQUXBTfCM9qwLSVQI8eHBgAAgIIILgrSO/DS8L0c0nHXEVwUzPhICdQg5I0AAQAAmo7gwhHd4bsfSqlUlcGFgwCMEigAAIAAEFw4FFKpVBXBhYPzQQkUAABAQAguShBCqVSZwYX2VUgW53bOX0EJFAAAQIAILkrkc6lUWcFFwdGyhhIoAACAcBFclMzBdKRSuA4uHIyWPdWgghIoAACAQBFcVMTB4tspV8GFg+CJjfAAAAAaguCiYg7KhpxwEVw4KPu6p4HFuftnCAAAgKoRXNTAQcNzYUWCCwcN60daAnVSzrMDAABAHQguaqSjWod1lErlCS4cHO9Yg4oo578HAACAxwguPFDH6NoswYWr0bI6XpYSKAAAgIYiuPBIlaNrbYOLTi/qamDA7toAAABYiODCM1WNrk0LLhgtCwAAgKwILjxV9ujaWcGF9lRsavZkNeevZrQsAABASxFceK7E0bXXjTGSJVkzxqzof/MGFDFGywIAALQYwUUAfBhdm4LRsgAAACC4CEmdo2vnYLQsAAAAHiG4CJCDCU4u3GG0LAAAAJIILgJW5ejaBCmB2ma0LAAAAC4juAiclkrtlj26VoOKXUbLAgAAYB6Ci4bQIEOyGNuOMxmHWv5EUAEAAICFCC4aSHsyurpnRZ6+DAkoJJiIKH8CAACALYKLhtOMRrw5nkn8NybjY6UpW4KIE8bJAgAAIBdjzP8HgQhY3jod6HMAAAAASUVORK5CYII=";

?>

	<div class="header">
		<a href=<?php echo esc_url($beagleUrl); ?> target="_blank">
			<img src="<?php echo $beagleHeaderImage; ?>" alt="Beagle Security">
		</a>
	</div>
	<?php
	global $wpdb;
	$Beagle_WP_scan_table = $wpdb->prefix . "beagleScanData";
	$getDataFromDb = $wpdb->get_results("SELECT * FROM $Beagle_WP_scan_table");
	if (!$getDataFromDb) { ?>
		<form method="POST" class="input-form font-monte">
			<div class="form-group access">
				<label class="label mb-0">Access token</label>
				<input class="form-control" type="text" minlength="32" name="access_token" id="access_token" placeholder="Enter Access Token" required>
			</div>
			<div class="form-group appdiv">
				<label class="label my-0">Application token</label>
				<input class="form-control" type="text" minlength="32" name="application_token" placeholder="Enter Application Token" id="application_token" required>
			</div>
			<div class="form-group m-auto">
				<button class="startBtn" type="submit" name="startVerify" onclick="BeagleWP_Token_Input()">
					<span id="continueSave">CONTINUE</span>
					<span id="spinnerSave" style="display: none;" class="loaderFirst"></span>
				</button>
			</div>
			<div class="form-group help">
				<a href="<?php echo esc_url($beagleHelp); ?>" target="_blank">Need help on tokens?</a>
			</div>
		</form>
	<?php
	}
	if ($getDataFromDb) { ?>
		<div class="row w-100 justify-content-center font-monte">
			<div class="container mx-auto">
				<div class="col-sm-12 d-flex mb-2 w-100 mx-auto head-main">
					<div class="col-sm p-0 app-image my-auto">
						<div class="application-image">
						</div>
					</div>
					<div class="col-sm-10 px-4 my-auto">
						<div class="row w-100">
							<span class="col-sm-5 d-block">
								<div class="row titledomain">
									<?php echo $getDataFromDb[0]->title; ?>
								</div>
								<div class="row urldomain">
									<div class="form-group px-0">
										<label class="form-check-label"><?php echo $getDataFromDb[0]->url; ?></label>
										<?php
										if ($getDataFromDb[0]->verified == 1) {
											echo '<span class="dashicons dashicons-yes verify-icon ml-2" title="Domain verification completed successfully."></span>';
										}?>
									</div>
								</div>
							</span>
							<span class="col-sm-7 d-flex my-auto">
								<?php
								if ($getDataFromDb[0]->verified == 0) {
									echo '<span class="dashicons dashicons-no-alt close-icon " title="Domain verification not completed." id="notverifyiedicon"></span>';
									if ($getDataFromDb[0]->autoVerify == 0) {
										echo '<button class="btn verify-domain my-auto" id="verifyDomain" onclick="BeagleWP_verifyDomain_ByUser()">
									VERIFY DOMAIN</button>
									<button class="btn verify-domain my-auto startVerify" id="verifyDomainHide" disabled>
									<span class="spinner-border spinner-border-sm my-auto" role="status" aria-hidden="true"></span>
									<span class="my-auto">Verifying...</span>
									</button>
									';
									} else {
										echo '<span class="my-auto">
										<span class="errorMsg my-auto">Domain verification failed!</span>
										<span onclick="BeagleWP_show_Msg()" class="dashicons dashicons-info-outline infoicon my-auto"></span>
									</span>';
									}
								}
								?>
								<span id="verifyError" class="my-auto" style="display: none;">
									<span class="errorMsg my-auto" id="verificationFailMsg">Domain verification failed!</span>
									<span onclick="BeagleWP_show_Msg()" class="dashicons dashicons-info-outline infoicon my-auto"></span>
								</span>
							</span>
						</div>
					</div>
					<div class="col-sm  p-0 my-auto d-flex justify-content-end">
						<?php
						if ($getDataFromDb[0]->runningStatus == "notRunning") {
							echo '<span class="dashicons dashicons-trash delete-icon" title="remove test" onclick="BeagleWP_delete_Confirm()"></span>';
						} else {
							echo '<span class="dashicons dashicons-trash delete-icon-hide" title="Test in running status"></span>';
						}
						?>
					</div>
				</div>
				<form method="POST" class="col-sm-12 d-flex justify-content-center test-box">
					<?php
					if ($getDataFromDb[0]->verified == "0") {
						echo '<button class="btn hidden-test" disabled>START TEST</button>';
					} else {
						if ($getDataFromDb[0]->runningStatus == "Running") {
							echo '<button type="submit" name="stopBeagleTest" class="btn stop-test">STOP TEST</button>';
						} else {
							if ($getDataFromDb[0]->status == "completed") {
								echo '<button type="submit" name="restartBeagleTest" class="btn start-test">RESTART TEST</button>';
							} else {
								echo '<button type="submit" name="startBeagleTest" class="btn start-test">START TEST</button>';
							}
						}
					}
					?>
				</form>
				<?php
				if ($getDataFromDb[0]->verified == "1" && $getDataFromDb[0]->runningStatus == "Running") { ?>
					<div class="col-sm-12 d-block justify-content-center status-box" id="statusbar">
						<div class="row">
							<div class="col-sm-6">
								<span class="ststustest" id="status"></span>
								<span class="test-msg" id="message"></span>
							</div>
							<div class="col-sm-6 d-flex justify-content-end">
							</div>
						</div>
						<div class="row">
							<div class="col-md-11 progressdiv my-auto">
								<div class="progress">
									<div id="progress" class="progress-bar" role="progressbar"></div>
								</div>
							</div>
							<div class="col-md-1 reloadicon my-auto float-left">
								<span name="statusGet" id="statusGet" onclick="BeagleWP_getStatus_Data()" class="dashicons dashicons-image-rotate rotate-icon reload-icon"></span>
								<span class="loadingRe" style="display: none;" id="spinner">
									<div class="loader"></div>
								</span>
							</div>
						</div>
					</div>
				<?php
				} ?>
				<div class="row px-3 resultblock" id="resultData">
					<div class="col-sm-12 score progressround d-flex justify-content-center">
						Your score is
					</div>
					<div class="col-sm-12 pt-2 progressround d-flex justify-content-center">
						<div class="c100 small" id="progressClass">
							<span id="progressCount"></span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill" id="fill"></div>
							</div>
						</div>
					</div>
					<div class="row p-0 m-0  w-100">
						<div class="col px-0 py-2 gentxt" id="resulttext">
							Last test result
						</div>
						<div class="col px-0 py-2 d-flex justify-content-end gendate align-items-baseline">
							Generated date: <span id="genDate"></span>
						</div>
					</div>
					<div class="col-sm-2 m-auto critical">
						<div class="row m-auto justify-content-center countnumber" id="criticalBug">

						</div>
						<div class="row m-auto justify-content-center counttext">
							Critical
						</div>
					</div>
					<div class="col-sm-2 m-auto high">
						<div class="row m-auto justify-content-center countnumber" id="highBug">

						</div>
						<div class="row m-auto justify-content-center counttext">
							High
						</div>
					</div>
					<div class="col-sm-2 m-auto medium">
						<div class="row m-auto justify-content-center countnumber" id="mediumBug">

						</div>
						<div class="row m-auto justify-content-center counttext">
							Medium
						</div>
					</div>
					<div class="col-sm-2 m-auto low">
						<div class="row m-auto justify-content-center countnumber" id="lowBug">

						</div>
						<div class="row m-auto justify-content-center counttext">
							Low
						</div>
					</div>
					<div class="col-sm-2 m-auto verylow">
						<div class="row m-auto justify-content-center countnumber" id="verylowBug">

						</div>
						<div class="row m-auto justify-content-center counttext">
							Very Low
						</div>
					</div>
					<div class="col-sm-2 m-auto total">
						<div class="row m-auto justify-content-center countnumber" id="totalBug">

						</div>
						<div class="row m-auto justify-content-center counttext">
							Total
						</div>
					</div>
					<div class="col-sm-12 pt-2 progressround d-flex justify-content-end">
						<div class="goto">For a detailed test report, go to <a href="<?php echo esc_url($beagleDetReport); ?>" target="_blank">Beagle Security</a></div>
					</div>
				</div>
			</div>
		</div>
		<?php
		if ($getDataFromDb[0]->verified == "1" && $getDataFromDb[0]->status != "completed") { ?>
			<script>
				function BeagleWP_getStatus_Data() {
					document.getElementById("statusGet").style.display = "none";
					document.getElementById("spinner").style.display = "block";
					BeagleWP_get_Data();
				}
				BeagleWP_get_Data();
				var timerVar = setInterval(BeagleWP_get_Data, 10000);
				<?php 
				if($getDataFromDb[0]->status == "completed"){?>
  					clearInterval(timerVar);
				<?php } ?>
			</script>
		<?php } else if ($getDataFromDb[0]->verified == "1" && $getDataFromDb[0]->status == "completed") { ?>
			<script>
				BeagleWP_get_Result();	
			</script>
<?php }
	}
}
