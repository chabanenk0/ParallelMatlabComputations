function lines = wwwread(fetchurl,numinputs,c)
%WWWREAD Parse requested URL data.
%   RETDAT = WWWREAD(FETCHURL,NUMINPUTS,C) retrieves and performs some
%   preliminary data parsing given the url, FETCHURL.   NUMINPUTS
%   is the number of varargin's determining if a date range is returned.
%   C is the Yahoo connection object.

%Create stream
%if isempty(c.ip)
  www = java.net.URL(fetchurl);
  %else
  %www = java.net.URL('http','192.168.0.10',3128,fetchurl);
 %end
is = www.openStream;

%Read stream of data
isr = java.io.InputStreamReader(is);
br = java.io.BufferedReader(isr);

%Parse return data
retdat = [];
%next_line = toCharArray(br.readLine)';  %First line contains headings, determine length
next_line ='';
%Delimiter is comma for all types of historical (used to be space for dividend data)
delim = ',';
headinglength = length(next_line)+1;
lines={};
%Loop through data
n=0;
while ischar(next_line)
  
  retdat = [retdat, 13, next_line];
  tmp = br.readLine;
  try
    next_line = toCharArray(tmp)';
    n=n+1;
    lines{n}=next_line;
  catch
    break;
  end
  %Convert dates if historical data
  
end

%Cleanup java objects
br.close; 
isr.close;
is.close;
