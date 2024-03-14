jQuery(document).ready(function ($) {
  const tipButtons = document.querySelectorAll('.tip-button');

  if (tipButtons.length > 0) {
    tipButtons.forEach((tipButton) => {
      tipButton.addEventListener('click', async function () {
        const userAccount = $(this).data('metamask-address');
        const price = $(this).data('metamask-amount');
        const yourAddress = userAccount;    
        const desiredNetwork = '1'; // Ethereum main network ID

        try {
          if (typeof window.ethereum === 'undefined' || typeof web3 === 'undefined') {
            // MetaMask extension not detected
            const el = document.createElement('div');
            el.innerHTML = "<a href='https://chrome.google.com/webstore/detail/metamask/nkbihfbeogaeaoehlefnkodbefgpgknn?hl=en' target='_blank'>Click Here</a> to install MetaMask extension";

            Swal.fire({
              title: extradata.const_msg.ext_not_detected, // Assuming extradata is defined elsewhere
              html: el,
              icon: 'warning',
            });
          } else {
            let accounts;          
            if (!ethereum.selectedAddress) {
              Swal.fire({
                text: 'Please wait while connection establishes',
                didOpen: () => {
                  Swal.showLoading();
                },
                allowOutsideClick: false,
              });
              accounts=await ethereum.request({ method: 'eth_requestAccounts' });
              if (ethereum.networkVersion !== desiredNetwork) {
              // Switch network
              await switchNetwork()
              }
             
            } else {
              if (ethereum.networkVersion !== desiredNetwork) {
                // Switch network
                await switchNetwork()
                }
              const result = await Swal.fire({
                title: 'Confirm amount in Ethereum',
                allowOutsideClick: false,
                html: `<input type="text" value="${price}" class="swal2-input" id="donation_amount" placeholder="Enter amount">`,
                preConfirm: () => {
                  const donationAmount = Swal.getPopup().querySelector('#donation_amount').value;
                  if (!donationAmount) {
                    Swal.showValidationMessage('Please enter an amount');
                  }
                  return { donationAmount };
                },
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                reverseButtons: true,
              });

              if (result.isConfirmed) {
                Swal.fire({
                  title: "Confirm transaction from wallet",
                  icon: 'question',
                  didOpen: () => {
                    Swal.showLoading()
                  },
                  // imageUrl: extradata.url + "/assets/images/metamask.png",
                  allowOutsideClick: false,
                })
                await sendEtherFrom(ethereum.selectedAddress, result.value.donationAmount,yourAddress);
              }
            }
          }
        } catch (error) {
          console.error(error);
        }
      });
    });
  }

  async function switchNetwork(){
      Swal.fire({
        text: 'This application requires the main network. Click OK to switch the network.',
        icon: 'warning',
        didOpen: () => {
          Swal.showLoading()
        },
      }); 
      await ethereum.request({
        method: 'wallet_switchEthereumChain',
        params: [{ chainId: '0x1' }],
      });
      location.reload();    

  }
  
  async function sendEtherFrom(account, donationAmount,yourAddress) {
    try {
      const provider = new ethers.providers.Web3Provider(window.ethereum, 'any');
      const signer = provider.getSigner();
      
      const tx = {
        from: account,
        to: yourAddress,
        value: ethers.utils.parseEther(donationAmount)._hex,
        gasLimit: ethers.utils.hexlify('0x5208'), // 21000
      };
      
      const trans = await signer.sendTransaction(tx);
      
      Swal.fire({
        title: 'Transaction in Process! Please Wait',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });    
      await trans.wait();
      Swal.fire({
        title: 'Transaction completed successfully!',
        icon: 'success',
        timer: 2000,
      });
    } catch (error) {
      if (error.code === 4001) {
        Swal.fire({
          title: 'Transaction rejected',
          icon: 'error',
          timer: 2000,
        });
      } else {
        console.error(error);
        Swal.fire({
          title: error.message,
          icon: 'error',
          timer: 2000,
        });
      }
    }
  }
});
