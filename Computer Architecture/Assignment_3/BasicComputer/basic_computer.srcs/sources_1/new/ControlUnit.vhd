library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

entity ControlUnit is
    Port (
        in_op      : in std_logic_vector (11 downto 0);
        in_status  : in std_logic_vector (3 downto 0);
        enableA    : out std_logic;
        enableB    : out std_logic;
        selA       : out std_logic_vector (1 downto 0);
        selB       : out std_logic_vector (1 downto 0);
        loadPC     : out std_logic;
        selALU     : out std_logic_vector (2 downto 0);
        W          : out std_logic;
        incSP      : out std_logic;
        decSP      : out std_logic;
        LSP        : out std_logic;
        sub        : out std_logic;
        selDin     : out std_logic_vector (1 downto 0);
        selAdd     : out std_logic_vector (3 downto 0);
        loadBP     : out std_logic;
        selBP      : out std_logic;
        selPC      : out std_logic
        );
end ControlUnit;

architecture Behavioral of ControlUnit is

signal data_out : std_logic_vector(23 downto 0) := (others => '0');
signal c : std_logic;
signal z : std_logic;
signal n : std_logic;
signal o : std_logic;

-- Definicion de vector para simular if con compuertas and
signal c_vector : std_logic_vector(23 downto 0) := (others => '0');
signal z_vector : std_logic_vector(23 downto 0) := (others => '0');
signal n_vector : std_logic_vector(23 downto 0) := (others => '0');
signal o_vector : std_logic_vector(23 downto 0) := (others => '0');

begin

-- Conexiones status
z <= in_status(0);
n <= in_status(1);
c <= in_status(2);
o <= in_status(3);

c_vector(0) <= c;
c_vector(1) <= c;
c_vector(2) <= c;
c_vector(3) <= c;
c_vector(4) <= c;
c_vector(5) <= c;
c_vector(6) <= c;
c_vector(7) <= c;
c_vector(8) <= c;
c_vector(9) <= c;
c_vector(10) <= c;
c_vector(11) <= c;
c_vector(12) <= c;
c_vector(13) <= c;
c_vector(14) <= c;
c_vector(15) <= c;
c_vector(16) <= c;
c_vector(17) <= c;
c_vector(18) <= c;
c_vector(19) <= c;
c_vector(20) <= c;
c_vector(21) <= c;
c_vector(22) <= c;
c_vector(23) <= c;

z_vector(0) <= z;
z_vector(1) <= z;
z_vector(2) <= z;
z_vector(3) <= z;
z_vector(4) <= z;
z_vector(5) <= z;
z_vector(6) <= z;
z_vector(7) <= z;
z_vector(8) <= z;
z_vector(9) <= z;
z_vector(10) <= z;
z_vector(11) <= z;
z_vector(12) <= z;
z_vector(13) <= z;
z_vector(14) <= z;
z_vector(15) <= z;
z_vector(16) <= z;
z_vector(17) <= z;
z_vector(18) <= z;
z_vector(19) <= z;
z_vector(20) <= z;
z_vector(21) <= z;
z_vector(22) <= z;
z_vector(23) <= z;

n_vector(0) <= n;
n_vector(1) <= n;
n_vector(2) <= n;
n_vector(3) <= n;
n_vector(4) <= n;
n_vector(5) <= n;
n_vector(6) <= n;
n_vector(7) <= n;
n_vector(8) <= n;
n_vector(9) <= n;
n_vector(10) <= n;
n_vector(11) <= n;
n_vector(12) <= n;
n_vector(13) <= n;
n_vector(14) <= n;
n_vector(15) <= n;
n_vector(16) <= n;
n_vector(17) <= n;
n_vector(18) <= n;
n_vector(19) <= n;
n_vector(20) <= n;
n_vector(21) <= n;
n_vector(22) <= n;
n_vector(23) <= n;

o_vector(0) <= o;
o_vector(1) <= o;
o_vector(2) <= o;
o_vector(3) <= o;
o_vector(4) <= o;
o_vector(5) <= o;
o_vector(6) <= o;
o_vector(7) <= o;
o_vector(8) <= o;
o_vector(9) <= o;
o_vector(10) <= o;
o_vector(11) <= o;
o_vector(12) <= o;
o_vector(13) <= o;
o_vector(14) <= o;
o_vector(15) <= o;
o_vector(16) <= o;
o_vector(17) <= o;
o_vector(18) <= o;
o_vector(19) <= o;
o_vector(20) <= o;
o_vector(21) <= o;
o_vector(22) <= o;
o_vector(23) <= o;


-- Conexiones señales

enableA <= data_out(23);
enableB <= data_out(22);

selA(1) <= data_out(21);
selA(0) <= data_out(20);

selB(1) <= data_out(19);
selB(0) <= data_out(18);

loadPC <= data_out(17);

selALU(2) <= data_out(16);
selALU(1) <= data_out(15);
selALU(0) <= data_out(14);

W <= data_out(13);
incSP <= data_out(12);
decSP <= data_out(11);
LSP <= data_out(10);
sub <= data_out(9);

selDin(1) <= data_out(8);
selDin(0) <= data_out(7);

selAdd(3) <= data_out(6);
selAdd(2) <= data_out(5);
selAdd(1) <= data_out(4);
selAdd(0) <= data_out(3);

loadBP <= data_out(2);
selBP <= data_out(1);
selPC <= data_out(0);

with in_op select
    data_out <=
    
"000000000000000000011000" when "000000000000",
"100110000000000000011000" when "000000000001",
"010011000000000000011000" when "000000000010",
"100100000000000000011000" when "000000000011",
"010100000000000000011000" when "000000000100",
"100010000000000000011000" when "000000000101",
"010010000000000000011000" when "000000000110",
"100000000000000000011000" when "000000000111",
"100010000100000000011000" when "000000001000",
"010010000100000000011000" when "000000001001",
"100000000100000000011000" when "000000001010",
"100010001000000000011000" when "000000001011",
"010010001000000000011000" when "000000001100",
"100000001000000000011000" when "000000001101",
"100000001100000000011000" when "000000001110",
"010000001100000000011000" when "000000001111",
"100000010000000000011000" when "000000010001",
"010000010000000000011000" when "000000010010",
"100000010100000000011000" when "000000010011",
"010000010100000000011000" when "000000010100",
"000010000100000000011000" when "000000010101",
"000000000100000000011000" when "000000010110",
"000000100000000000011000" when "000000010111",
"000000100000000000011000" and z_vector when "000000011000",
"000000100000000000011000" and not n_vector and not z_vector when "000000011001",
"000000100000000000011000" and c_vector when "000000011010",
"000000100000000000011000" and o_vector when "000000011011",
"000000100000000000011000" when "000000011100",
"100101000000000000010000" when "000000011101",
"010101000000000000010000" when "000000011110",
"000011000010000000010000" when "000000011111",
"000110000010000000010000" when "000000100000",
"000011000010100000001000" when "000000100001",
"000110000010100000001000" when "000000100010",
"000000000001000000011000" when "000000100011",
"100101000000000000001000" when "000000100100",
"010101000000000000001000" when "000000100101",
"000000100010100010001000" when "000000100110",
"000000100000000000001001" when "000000100111",
"000000100010100110001000" when "000000101000",
"000000000000000000001100" when "000000101001",
"000000000000000000011110" when "000000101010",
"100101000000000000000000" when "000000101011",
"100101000000001000000000" when "000000101100",
"010101000000000000000000" when "000000101101",
"010101000000001000000000" when "000000101110",
"001000000000010000011000" when "000000101111",
"001000000100010000011000" when "000000110000",
"001000100000010000001001" when "000000110001",
(others => '0') when others; -- Por si acaso


end Behavioral;
