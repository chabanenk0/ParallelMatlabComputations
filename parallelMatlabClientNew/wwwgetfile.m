function files=wwwgetfile(url)
% функция, которая запрашивает файл задания на веб- или фтп-сервере,
% распаковывает его и возвращает путь к нему. 
global path_to_rar;
if (isempty(path_to_rar))
    path_to_rar='C:\Program Files\WinRAR\';
end
global path_to_wget;
if (isempty(path_to_wget))
    path_to_wget='E:\softSince20090830\wget\wget-1.10.2b\';
end
lastslash=strfind(url,'/');
lastslash=lastslash(length(lastslash));
filename=url(lastslash+1:length(url));
folder=strrep(filename,'.rar','');
try
    cd(folder)
    
catch
    
if (0) % реализация средствами вввгет ява

fetchurl=url;
%Create stream
%if isempty(c.ip)
  %www = java.net.URL(fetchurl);
  %else
  www = java.net.URL('http','192.168.0.10',3128,fetchurl);
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
fp=fopen(filename,'wb');
while ischar(next_line)
  try
      tmp = br.read;
      if (tmp==-1)
          break;
      end
  fprintf(fp,'%c',tmp);
  catch
    break;
  end
  %Convert dates if historical data
  
end

%Cleanup java objects
br.close; 
isr.close;
is.close;
fclose(fp);

else % реализация wget
    %system(['"' path_to_wget 'wget.exe" --proxy=on  -ehttp_proxy=http://192.168.0.10:3128 ' url ]);
    system(['"' path_to_wget 'wget.exe" ' url ]);
end
system(['mkdir ' folder]);

system(['"' path_to_rar '"rar e ' filename ' *.* ' folder '/']);

cd ( folder);
end
files=dir;
