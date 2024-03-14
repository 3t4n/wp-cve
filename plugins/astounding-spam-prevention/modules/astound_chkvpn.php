<?php

if (!defined('ABSPATH')) exit;

class astound_chkvpn extends astound_module { 
		public $searchName='VPN Host';
		public $searchlist=array(
		
# Dynamic Assigniment of PPPoE Hub-3 PK
'103.12.120.0/24 ',
# Dynamic Assigniment of PPPoE Hub-3 PK
'103.12.121.0/24 ',
# 103012122005 Dynamic Assigniment of PPPoE Hub-3 PK
'103.12.122.0/24 ',
# Static IP address PPPoE KH
'103.239.54.0/24 ',
# Madrid Gateway ES
'103.244.132.0/23 ',
# 104145225178 TEFINCOM S.A. NVPN-PNJ-2 (NET-104-145-225-176-1) US
'104.145.225.176/29 ',
# 104145235038 TEFINCOM S.A. NVPN-NYC-1 (NET-104-145-235-32-1) US
'104.145.235.32/28 ',
# Secure Internet LLC PUREVPN (NET... US
'104.243.240.0/20 ',
# Secure Internet LLC PUREVPN (NET... US
'104.250.160.0/19 ',
# BH Telecom, PPPoE dynamic pool1 ... BA
'109.163.160.0/21 ',
# BH Telecom PPPoE BRAS Sarajevo d... BA
'109.175.32.0/20 ',
# BH Telecom PPPoE BRAS Sarajevo d... BA
'109.175.48.0/20 ',
# BH Telecom, PPPoE dynamic pool B... BA
'109.175.96.0/20 ',
# Network for PPPoE clients termin... RU
'109.184.0.0/17 ',
# Network for PPPoE clients termin... RU
'109.184.128.0/17 ',
# VPN services from Private Intern... NL
'109.201.128.0/19 ',
# vpn dynamic pool RU
'109.201.192.0/20 ',
# vpn dynamic pool RU
'109.201.208.0/20 ',
# 109226255017 Network for PPPoE links RU
'109.226.224.0/19 ',
# AIST ISP, PPTP (physical persons) RU
'109.226.64.0/18 ',
# AirVPN.org IP Space NL
'109.232.224.0/21 ',
# 109234168066 Thales Gateways North GB
'109.234.168.0/21 ',
# Business-Svyaz LTD vpn clients RU
'109.235.190.0/24 ',
# Dianet for VPN and PPPoE dynamic... RU
'109.237.144.0/20 ',
# GPLHost VPS (Virtual Private Ser... AU
'117.121.242.0/24 ',
# ExpressVPN.com IP Space HK
'119.9.85.16/28 ',
# Bayan Broadband PPPoE PH
'121.96.248.0/21 ',
# BayanTel Broadband DSL - PPPoE PH
'121.97.144.0/20 ',
# BayanTel Broadband DSL - PPPoE_PE1 PH
'121.97.192.0/20 ',
# BayanTel Broadband DSL - PPPoE_PPE2 PH
'121.97.208.0/20 ',
# Proxy-N-VPN.com NET-138-128-121-... US
'138.128.121.0/25 ',
# Greece gateway GR
'141.105.164.0/23 ',
# Secure Internet LLC PUREVPN (NET... US
'162.246.184.0/22 ',
# 168001065182 VPNSecure Pty Ltd AU
'168.1.65.180/30 ',
# VPNSecure Pty Ltd AU
'168.1.66.224/28 ',
# PureVPN HK
'168.1.70.240/28 ',
# City-Telekom LLC PPPoE address pool RU
'176.32.128.0/20 ',
# City-Telekom LLC PPPoE address pool RU
'176.32.144.0/20 ',
# vpn dynamic pool RU
'178.18.96.0/20 ',
# VPN (PPTP,PPPoE) customers Revda... RU
'178.211.160.0/20 ',
# VPN (PPTP,PPPoE) customers Revda... RU
'178.211.176.0/21 ',
# 178211184196 VPN (PPPoE) customers Sverdlovsk reg. "Interra" Ltd. RU
'178.211.184.0/21 ',
# CZ.FIXNET - pppoe customers CZ
'178.248.56.0/21 ',
# NETSURF dynamic PPPoE pools BG
'178.254.224.0/19 ',
# NETSURF dynamic PPPoE pools BG
'178.254.232.0/21 ',
# Virtual Private Servers Range 3 NL
'178.255.197.0/24 ',
# 178255045108 VPNonline PL
'178.255.45.0/24 ',
# Anonine VPN Customers SE
'178.73.192.0/18 ',
# vpnsecure - Shayne McCulloch UA
'178.86.132.0/22 ',
# Gateway1 Clients 1 to 254 PK
'180.149.208.0/20 ',
# 183082016111 PPPoE IN
'183.82.16.0/24 ',
# 183082022097 PPPoE IN
'183.82.22.0/24 ',
# PPPoE dynamic pool RU
'185.11.148.0/22 ',
# 185148220011 User_net_1_pppoe RU
'185.148.220.0/24 ',
# 185157162040 OVPN Amsterdam NL
'185.157.162.0/24 ',
# CIZGI TELEKOMUNIKASYON ANONIM SI... TR
'185.22.187.0/24 ',
# 185003132186 Reverse-Proxy SE
'185.3.132.0/24 ',
# 185003133008 Reverse-Proxy SE
'185.3.133.0/24 ',
# Anonymizer, Inc. US
'185.47.100.0/22 ',
# Skynet Iletisim Hizmetleri Anoni... TR
'185.51.112.0/24 ',
# 185052069197 RialCom dynamic pppoe network for customers RU
'185.52.68.0/23 ',
# RialCom dynamic pppoe network fo... RU
'185.52.70.0/23 ',
# 185065134076 Mullvad is a VPN service that helps keep your online activity, identity and location private. NL
'185.65.132.0/22 ',
# RialCom dynamic pppoe network fo... RU
'185.68.118.0/23 ',
# RialCom dynamic pppoe network fo... RU
'185.7.154.0/23 ',
# RialCom dynamic pppoe network fo... RU
'185.72.190.0/23 ',
# ADSL PPPOE END USER 1 TR
'185.8.12.0/22 ',
# Inulogic Virtual Private Servers FR
'185.81.156.0/22 ',
# Cassiano Zanon - CZNET Provedor ... BR
'186.237.16.0/21 ',
# AIST ISP, PPTP (physical persons) RU
'188.122.224.0/19 ',
# Anonymizer, Inc. US
'188.241.193.0/24 ',
# P-t-P (PPTP, PPPoE, VLAN) client... RU
'188.64.220.0/22 ',
# vpn dynamic pool RU
'188.68.192.0/19 ',
# 188093133214 PPPoE access to Internet RU
'188.93.133.0/24 ',
# Secure Internet LLC PUREVPN (NET... US
'192.253.240.0/20 ',
# Anonymous S.A. EU
'193.200.150.0/24 ',
# VPN Services AX
'194.112.0.0/20 ',
# KYRGYZTELECOM_ADSL_PPPOE KG
'195.114.248.0/21 ',
# Dynamic pool for PPPoE clients (... RU
'195.144.192.0/19 ',
# Turk Telekomunikasyon Anonim Sirketi TR
'195.175.0.0/17 ',
# 195032106250 PPPoE Customers IP POOLs IT
'195.32.106.0/24 ',
# 197148122133 IP addresses use by ADSL clients on BRAS for dynamic allocation on ppoe connections TG
'197.148.120.0/22 ',
# 197148102148 IP addresses use by ADSL clients on BRAS for dynamic allocation on ppoe connections TG
'197.148.96.0/20 ',
# Cyberdynes VPN users, block 01. LR
'197.231.221.0/24 ',
# EMCALI - RANGOS FIJOS PPPoE /32 CO
'200.29.111.0/24 ',
# Tachyon VPN IP Pool ID
'202.182.48.0/21 ',
# Customer Allocation for PPPOE Di... BD
'202.53.174.0/24 ',
# Canon (China) Co,Ltd CN
'202.99.29.64/26 ',
# Castle VPN US
'209.222.240.0/20 ',
# vpn dynamic pool RU
'212.21.0.0/21 ',
# vpn dynamic pool RU
'212.21.16.0/21 ',
# vpn dynamic pool RU
'212.21.24.0/21 ',
# vpn dynamic pool RU
'212.21.28.0/24 ',
# vpn dynamic pool RU
'212.21.31.0/24 ',
# vpn dynamic pool RU
'212.21.8.0/21 ',
# PPPoE Pool RU
'212.232.0.0/20 ',
# Michael Renner TOR Gateway AT
'212.232.24.0/21 ',
# PPPoE dynamic pool RU
'212.232.32.0/19 ',
# IPs for masquerading PPPoE dialu... BG
'212.25.59.0/24 ',
# Customers with PPPoE dialup publ... BG
'212.25.61.0/24 ',
# PPPOE enterprise customers RU
'212.33.239.0/24 ',
# Corporate customers PPPoE network RU
'212.33.246.0/24 ',
# 212049083006 IP MPLS BUSINESS VPN POOL KE
'212.49.83.0/24 ',
# Element. PPTP/PPPOE services UA
'212.87.160.0/23 ',
# KYRGYZTELECOM_ADSL_PPPOE KG
'212.97.0.0/24 ',
# KYRGYZTELECOM_ADSL_PPPOE KG
'212.97.15.0/24 ',
# KYRGYZTELECOM_ADSL_PPPOE KG
'212.97.31.0/24 ',
# PPPOE DYNAMIK POOL RU
'213.129.104.0/21 ',
# PPPOE DYNAMIK POOL RU
'213.129.112.0/23 ',
# PPPOE DYNAMIK POOL RU
'213.129.125.0/24 ',
# KYRGYZTELEKOM-PPPOE KG
'213.145.159.0/24 ',
# AIST ISP, PPTP (physical persons) RU
'213.178.32.0/21 ',
# AIST ISP, PPTP (physical persons) RU
'213.178.40.0/21 ',
# PPPoE clients of NETIS TELECOM RU
'213.187.112.0/20 ',
# PPPoE pool (site 1) RU
'217.113.112.0/20 ',
# ONE PPPoE Broadband Internet MK
'217.16.80.0/20 ',
# 217170254170 Petiak System PPPOE Clients IR
'217.170.254.0/23 ',
# ComNet Burgas Additional PPPoE BG
'217.174.60.0/24 ',
# Dynamic pool for PPPoE Customer LV
'217.24.64.0/20 ',
# UA-CDS-UA-RIPE-CLNTS1-PPPOE-180609 UA
'217.67.64.0/22 ',
# Dynamic pool for PPPoE users UA
'217.73.88.0/21 ',
# IBS Ltd, DATA FORT project VPN N... RU
'217.74.32.0/20 ',
# INTOPROXY SR NET-23-254-56-0-1 (... US
'23.254.56.0/24 ',
# INTOPROXY SR NET-23-254-78-0-1 (... US
'23.254.78.0/24 ',
# Canonical range for HV0006 GB
'31.193.128.0/20 ',
# VPN (PPPoE) customers Pervourals... RU
'31.28.104.0/21 ',
# AIST ISP, PPTP (physical persons) RU
'31.28.32.0/19 ',
# 031028101202 VPN (PPPoE) customers Sverdlovsk reg. "Interra" Ltd. RU
'31.28.96.0/21 ',
# vpn dynamic pool RU
'31.47.112.0/22 ',
# vpn dynamic pool RU
'31.47.116.0/22 ',
# vpn dynamic pool RU
'31.47.120.0/22 ',
# vpn dynamic pool RU
'31.47.124.0/22 ',
# RialCom dynamic pppoe network fo... RU
'37.1.0.0/18 ',
# VPN (PPPoE) customers Pervourals... RU
'37.1.136.0/21 ',
# VPN (PPPoE) customers Pervourals... RU
'37.131.192.0/22 ',
# VPN (PPPoE) customers Lesnoy Sve... RU
'37.131.208.0/21 ',
# VPNSECURE PTY LTD GB
'37.139.64.0/24 ',
# BH Telecom PPPoE BRAS Sarajevo d... BA
'37.203.112.0/21 ',
# BH Telecom PPPoE BRAS Sarajevo d... BA
'37.203.120.0/22 ',
# BH Telecom PPPoE BRAS dynamic po... BA
'37.203.96.0/20 ',
# KYRGYZTELECOM_ADSL_PPPOE KG
'37.218.128.0/19 ',
# KYRGYZTELECOM_ADSL_PPPOE KG
'37.218.160.0/19 ',
# 037247105072 DGN Teknoloji Anonim Sirketi TR
'37.247.105.0/24 ',
# 037028159200 VPNonline PL
'37.28.159.0/24 ',
# 037032020225 Used for PPPoE Server and end users IR
'37.32.20.0/24 ',
# 037032029033 Used for PPPoE Server and end users IR
'37.32.29.0/24 ',
# Smoltelecom PPPoE (dynamic IPs f... RU
'37.44.40.0/21 ',
# PPPOE DYNAMIK POOL RU
'37.58.32.0/21 ',
# L2TP VPN broadband connections RU
'37.8.154.0/24 ',
# 043231076205 Gateway Online Access Limited BD
'43.231.76.0/22 ',
# Dynamic Assignment of PPPoE Hub-3 PK
'43.245.131.0/24 ',
# 045072058033 ProxyVPN NET-45-72-58-0-1 (NET-45-72-58-0-1) US
'45.72.58.0/25 ',
# Anonymizer, Inc. US
'46.102.239.0/24 ',
# Individual PPPoE customers RU
'46.146.100.0/22 ',
# Individual PPPoE customers RU
'46.146.108.0/22 ',
# 046146116239 Individual PPPoE customers RU
'46.146.116.0/22 ',
# Individual PPPoE customers RU
'46.146.120.0/22 ',
# Individual PPPoE customers RU
'46.146.136.0/22 ',
# Individual PPPoE customers RU
'46.146.152.0/22 ',
# Individual PPPoE customers RU
'46.146.160.0/22 ',
# 046146179180 Individual PPPoE customers RU
'46.146.176.0/22 ',
# Individual PPPoE customers RU
'46.146.184.0/22 ',
# 046146193220 Individual PPPoE customers RU
'46.146.192.0/22 ',
# Individual PPPoE customers RU
'46.146.204.0/22 ',
# Individual PPPoE customers RU
'46.146.215.0/24 ',
# Individual PPPoE customers RU
'46.146.219.0/24 ',
# 046147012249 Individual PPPoE customers RU
'46.147.12.0/22 ',
# Individual PPPoE customers RU
'46.147.36.0/22 ',
# Individual PPPoE customers RU
'46.147.44.0/22 ',
# VPN services from Private Intern... NL
'46.166.184.0/21 ',
# vpn dynamic pool RU
'46.167.64.0/20 ',
# vpn dynamic pool RU
'46.167.80.0/21 ',
# vpn dynamic pool RU
'46.167.88.0/21 ',
# vpn dynamic pool RU
'46.167.96.0/19 ',
# 046020014122 DGN Teknoloji Anonim Sirketi TR
'46.20.14.0/24 ',
# PPPOE customers in Qazvin IR
'46.209.0.0/21 ',
# PPPOE customers in Sari IR
'46.209.12.0/23 ',
# PPPOE customers in Qazvin IR
'46.209.7.0/24 ',
# Amagicom AB - Mullvad VPN Services SE
'46.21.96.0/22 ',
# PPPoE dynamic pool RU
'46.229.176.0/21 ',
# 046243146017 GZ Systems Limited - Colocation in Lebanon LB
'46.243.146.0/24 ',
# Anonine VPN Customers SE
'46.246.0.0/16 ',
# 046029248238 Reverse-Proxy SE
'46.29.248.0/23 ',
# 046029250062 Reverse-Proxy SE
'46.29.250.0/23 ',
# L2TP VPN broadband connections RU
'46.37.128.0/24 ',
# L2TP VPN broadband connections RU
'46.37.134.0/24 ',
# 046037193074 ICN pppoe subcribers UA
'46.37.193.0/24 ',
# 005133008143 VPNonline PL
'5.133.8.0/24 ',
# StrongVPN Network GB
'5.144.152.0/23 ',
# EnterVPN network SE
'5.153.232.0/22 ',
# EnterVPN network SE
'5.153.237.0/24 ',
# 005157006051 Reverse-Proxy SE
'5.157.6.0/24 ',
# 005157007019 Reverse-Proxy SE
'5.157.7.0/24 ',
# Individual PPPoE customers RU
'5.166.160.0/22 ',
# Individual PPPoE customers RU
'5.166.164.0/22 ',
# 005166172246 Individual PPPoE customers RU
'5.166.172.0/22 ',
# Individual PPPoE customers RU
'5.166.176.0/22 ',
# VPN services GB
'5.199.172.0/22 ',
# OpenVPN Tunnel SE
'5.254.135.0/24 ',
# VPNTunnel Network SE
'5.254.139.0/24 ',
# VPNTunnel Network SE
'5.254.141.0/24 ',
# VPNTunnel Network SE
'5.254.143.0/24 ',
# Anonine VPN SE
'5.254.144.0/21 ',
# VPNTunnel Network SE
'5.254.153.0/24 ',
# VPNTunnel Network SE
'5.254.155.0/24 ',
# VPNTunnel Network SE
'5.254.156.0/24 ',
# PPPOE DYNAMIK POOL RU
'5.254.176.0/21 ',
# AIST ISP, PPTP (physical persons) RU
'5.28.16.0/20 ',
# NETWAY PPPOE END USERS TR
'5.63.32.0/19 ',
# AIST ISP, PPTP (physical persons) RU
'62.106.96.0/20 ',
# Rialcom clients Chehov PPPoE RU
'62.176.16.0/20 ',
# Turk Telekomunikasyon Anonim Sirketi TR
'62.248.0.0/17 ',
# PPPoE dynamic pool BA
'62.68.112.0/23 ',
# Positive Networks VPN Solutions ... US
'67.202.172.0/24 ',
# PPPoE users SK
'77.234.224.0/19 ',
# PPPoE user pool RU
'77.40.122.0/24 ',
# For PPPoE Customers RU
'77.43.224.0/19 ',
# PPPoE customers UA
'77.91.128.0/18 ',
# vpnsecure - Shayne McCulloch UA
'78.109.16.0/20 ',
# PPPOE Dialer IPs for HOME1MB SA
'78.110.0.0/20 ',
# ONE PPPoE Broadband Internet Loc2 MK
'79.126.128.0/18 ',
# ONE PPPoE Broadband Internet MK
'79.126.192.0/18 ',
# vpn dynamic pool RU
'79.134.24.0/24 ',
# vpn dynamic pool RU
'79.134.25.0/24 ',
# vpn dynamic pool RU
'79.134.26.0/24 ',
# vpn dynamic pool RU
'79.134.28.0/24 ',
# vpn dynamic pool RU
'79.134.29.0/24 ',
# vpn static assignment RU
'79.134.7.0/24 ',
# Cyberghost VPN FR
'79.141.163.0/24 ',
# Link addresses for PPPoE-clients UA
'80.249.224.0/20 ',
# PPPOE-USERS RU
'80.80.192.0/19 ',
# Industrial Telecom VPN RU
'81.20.200.0/22 ',
# VPN network for customers RU
'81.5.117.0/24 ',
# VPN network RU
'81.5.97.0/24 ',
# 081088211180 PPPoE peers RU
'81.88.208.0/21 ',
# For xDSL and FTTx PPPoE pools an... RU
'82.140.192.0/21 ',
# For xDSL and FTTx PPPoE pools an... RU
'82.140.200.0/21 ',
# PPPoE and FTTX dynamic and stati... RU
'82.140.208.0/21 ',
# PPPoE and FTTX dynamic and stati... RU
'82.140.216.0/21 ',
# PPPoE and FTTX dynamic and stati... RU
'82.140.224.0/21 ',
# For PPPoE users RU
'82.140.232.0/21 ',
# PPPoE network RU
'82.140.240.0/21 ',
# Network for PPPoE clients termin... RU
'82.208.100.0/23 ',
# Network for PPPoE clients termin... RU
'82.208.124.0/23 ',
# Network for dynamic PPPoE cliens RU
'82.208.78.0/24 ',
# Network for dynamic PPPoE cliens RU
'82.208.86.0/24 ',
# Network for dynamic PPPoE cliens RU
'82.208.92.0/24 ',
# Network for PPPoE clients termin... RU
'82.208.99.0/24 ',
# Beeline-Moscow GPRS Firewall RU
'83.220.236.0/23 ',
# Beeline-Moscow GPRS Firewall RU
'83.220.238.0/23 ',
# IP PPPoE dynamic pool RU
'83.69.225.0/24 ',
# PPPoE Networks Tattelecom RU
'84.18.116.0/24 ',
# PPPoE Networks Tattelecom RU
'84.18.117.0/24 ',
# PPPoE Networks Tattelecom RU
'84.18.119.0/24 ',
# Internet Gateway, Saudi Telecom ... SA
'84.235.110.0/24 ',
# PPPOE Customers DE
'84.46.0.0/17 ',
# Turk Telekomunikasyon Anonim Sirketi TR
'85.111.0.0/17 ',
# AIST ISP, PPTP (physical persons) RU
'85.114.160.0/19 ',
# Firewall pool in Stavropol RU
'85.115.248.0/25 ',
# Firewall pool in Stavropol RU
'85.115.248.128/25 ',
# CIZGI TELEKOMUNIKASYON ANONIM SI... TR
'85.159.68.0/24 ',
# ONE PPPoE Broadband Internet MK
'85.30.120.0/21 ',
# Virtual private servers LV
'85.31.96.0/21 ',
# static and PPPoE xDSL links in K... RU
'87.225.0.0/17 ',
# Comtel vpn client access RU
'87.254.136.0/22 ',
# ER-Telecom-Volgograd PPPoE subsc... RU
'88.87.64.0/19 ',
# PPPoE dynamic pool RU
'89.107.32.0/21 ',
# Network for PPPoE clients termin... RU
'89.109.52.0/24 ',
# Network for PPPoE clients termin... RU
'89.109.9.0/24 ',
# PPPoE dynamic address pool RU
'89.112.0.0/17 ',
# BH Telecom PPPoE BRAS dynamic po... BA
'89.146.128.0/20 ',
# BH Telecom PPPoE BRAS dynamic po... BA
'89.146.144.0/20 ',
# BH Telecom PPPoE BRAS dynamic po... BA
'89.146.160.0/20 ',
# 089146185150 BH Telecom PPPoE BRAS dynamic pool #2 Tuzla BA
'89.146.176.0/20 ',
# 089146080189 Dynamic address pool for PPPoE clients UZ
'89.146.64.0/18 ',
# Virtual Private Servers for Cust... CZ
'89.187.128.0/19 ',
# CIZGI TELEKOMUNIKASYON ANONIM SI... TR
'89.19.26.0/24 ',
# FASTtelco PPPOE MSF range KW
'89.203.64.0/22 ',
# VPNINT-ADSL-CORP-128KST-STATIC SA
'89.237.128.0/19 ',
# Anonymizer, Inc. US
'89.39.147.0/24 ',
# 089047015233 VPN Services HK
'89.47.15.0/24 ',
# 091108176006 Reverse-Proxy SE
'91.108.176.0/24 ',
# 091108177085 Reverse-Proxy SE
'91.108.177.0/24 ',
# 091108178013 Reverse-Proxy SE
'91.108.178.0/24 ',
# VPN NETWORK SE
'91.108.180.0/24 ',
# AIST ISP, PPTP (physical persons) RU
'92.240.128.0/20 ',
# PPPoE xDSL links in Obluchie vil... RU
'92.37.128.0/17 ',
# PPPoE Pool AL
'92.60.16.0/23 ',
# PPPoE Pool AL
'92.60.24.0/21 ',
# wired pppoe dsl subscribers KG
'92.62.74.0/24 ',
# wired pppoe dsl subscribers KG
'92.62.78.0/24 ',
# VPN (PPTP, PPPoE) customers Perv... RU
'94.190.0.0/18 ',
# VPN (PPPoE) customers Polevskoy ... RU
'94.190.72.0/21 ',
# Static PPPoE Pool 1 BG
'94.236.164.0/23 ',
# CIZGI TELEKOMUNIKASYON ANONIM SI... TR
'94.73.137.0/24 ',
# Canonical range for E11 GB
'94.76.192.0/18 ',
# Pushkino PPTP/NAT Services RU
'95.129.60.0/22 ',
# Octopusnet Dynamic VPN RU
'95.154.80.0/21 ',
# Octopusnet Dynamic VPN RU
'95.154.88.0/21 ',
# Network for PPPoE clients termin... RU
'95.37.0.0/18 ',
# Network for PPPoE clients termin... RU
'95.37.128.0/17 ',
# Network for PPPoE clients termin... RU
'95.37.64.0/18 ',
# Turk Telekomunikasyon Anonim Sirketi TR
'95.6.0.0/17 ',
# Zvenigorod PPPoE pool RU
'95.72.136.0/21 ',
# Sergiev-Posad PPPoE pool RU
'95.72.168.0/21 ',
# Serpukhov PPPoE pool RU
'95.72.32.0/21 ',
# Kubinka PPPoE pool RU
'95.72.72.0/21 ',
# Kolomna PPPoE pool RU
'95.72.80.0/20 '

		
	);
}
?>
